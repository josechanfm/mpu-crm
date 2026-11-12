import express from 'express';
import fs from 'fs/promises';
import os from 'os';
import path from 'path';
import { createRequire } from 'module';
import { pathToFileURL } from 'url';
import { createCanvas } from '@napi-rs/canvas';
import { getDocument } from 'pdfjs-dist/legacy/build/pdf.mjs';

const require = createRequire(import.meta.url);
const pdfjsRoot = path.dirname(require.resolve('pdfjs-dist/package.json'));
const PAGE_SCALE = 2;
const THUMB_SCALE = 0.5;
const CONCURRENCY = Math.max(1, Math.min(2, os.availableParallelism?.() ?? os.cpus().length));

class NodeCanvasFactory {
    create(width, height) {
        const canvas = createCanvas(width, height);
        return { canvas, context: canvas.getContext('2d') };
    }

    reset(canvasAndContext, width, height) {
        canvasAndContext.canvas.width = width;
        canvasAndContext.canvas.height = height;
    }

    destroy(canvasAndContext) {
        if (!canvasAndContext.canvas) return;
        canvasAndContext.canvas.width = 0;
        canvasAndContext.canvas.height = 0;
        canvasAndContext.canvas = null;
        canvasAndContext.context = null;
    }
}

function dirPath(relativeDir) {
    const resolved = path.resolve(pdfjsRoot, relativeDir);
    return resolved.endsWith(path.sep) ? resolved : resolved + path.sep;
}

export async function renderBook(pdfPath, pageFolder, thumbFolder) {
    const data = new Uint8Array(await fs.readFile(pdfPath));
    const canvasFactory = new NodeCanvasFactory();
    const pdf = await getDocument({
        data,
        disableFontFace: true,
        useSystemFonts: false,
        enableXfa: false,
        isEvalSupported: false,
        cMapUrl: dirPath('cmaps'),
        cMapPacked: true,
        standardFontDataUrl: dirPath('standard_fonts'),
        canvasFactory,
    }).promise;

    await fs.mkdir(pageFolder, { recursive: true });
    await fs.mkdir(thumbFolder, { recursive: true });

    const pageNumbers = Array.from({ length: pdf.numPages }, (_, index) => index + 1);
    let cursor = 0;
    const worker = async () => {
        while (cursor < pageNumbers.length) {
            const pageNumber = pageNumbers[cursor++];
            await renderPage(pdf, canvasFactory, pageNumber, pageFolder, thumbFolder);
        }
    };
    const workers = Math.min(CONCURRENCY, pageNumbers.length);
    await Promise.all(Array.from({ length: workers }, () => worker()));

    const pages = pdf.numPages;
    await pdf.cleanup();
    await pdf.destroy();
    return pages;
}

async function renderPage(pdf, canvasFactory, pageNumber, pageFolder, thumbFolder) {
    const page = await pdf.getPage(pageNumber);
    const viewport = page.getViewport({ scale: PAGE_SCALE });
    const canvasAndContext = canvasFactory.create(viewport.width, viewport.height);
    try {
        await page.render({
            canvasContext: canvasAndContext.context,
            viewport,
            canvasFactory,
        }).promise;

        const pageCanvas = canvasAndContext.canvas;
        await fs.writeFile(
            path.join(pageFolder, `${pageNumber}.jpg`),
            pageCanvas.toBuffer('image/jpeg', 82),
        );

        const ratio = THUMB_SCALE / PAGE_SCALE;
        const thumb = createCanvas(
            Math.max(1, Math.round(viewport.width * ratio)),
            Math.max(1, Math.round(viewport.height * ratio)),
        );
        thumb.getContext('2d').drawImage(pageCanvas, 0, 0, thumb.width, thumb.height);
        await fs.writeFile(
            path.join(thumbFolder, `${pageNumber}.jpg`),
            thumb.toBuffer('image/jpeg', 75),
        );
    } finally {
        page.cleanup();
        canvasFactory.destroy(canvasAndContext);
    }
}

const app = express();
app.use(express.urlencoded({ extended: false }));
app.use(express.json());

app.get('/health', (_req, res) => {
    res.status(200).json({ ok: true });
});

app.get('/', (_req, res) => {
    res.type('html').send(`<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>PDF worker link test</title>
<style>
body { font-family: sans-serif; max-width: 42rem; margin: 2rem auto; padding: 0 1rem; line-height: 1.45; }
code { background: #f2f2f2; padding: 0.1rem 0.35rem; }
button { margin-top: 0.5rem; }
#result { white-space: pre-wrap; }
.ok { color: #0a7a32; }
.bad { color: #a40000; }
</style>
</head>
<body>
<h1>PDF conversion service</h1>
<p>This page is served by the worker. If you can read it, this host and port reach the service.</p>
<p>Convert URL: <code id="url"></code></p>
<p>Laravel posts <code>thumb</code>, <code>folder</code>, and <code>pdf</code> to <code>PDF2IMG_URL</code>, which defaults to <code>http://127.0.0.1:3030/convert</code> inside the app container.</p>
<button id="ping" type="button">Test /convert</button>
<p id="result"></p>
<script>
const url = location.origin + '/convert';
document.getElementById('url').textContent = url;
document.getElementById('ping').onclick = async () => {
    const result = document.getElementById('result');
    result.className = '';
    result.textContent = 'Contacting ' + url + '...';
    try {
        const response = await fetch(url, {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'thumb=&folder=&pdf='
        });
        const text = await response.text();
        let json = null;
        try { json = JSON.parse(text); } catch (e) {}
        if (json && (Object.prototype.hasOwnProperty.call(json, 'pages') || json.error)) {
            result.className = 'ok';
            result.textContent = 'Service link is correct.\\n' + response.status + ' ' + text;
        } else {
            result.className = 'bad';
            result.textContent = 'Reached a server, but it is not this converter.\\n' + response.status + ' ' + text;
        }
    } catch (error) {
        result.className = 'bad';
        result.textContent = 'Could not reach ' + url + '\\n' + error;
    }
};
</script>
</body>
</html>`);
});

app.post('/convert', async (req, res) => {
    const pageFolder = req.body.folder;
    const pdfPath = req.body.pdf;
    const thumbFolder = req.body.thumb;
    try {
        const pages = await renderBook(pdfPath, pageFolder, thumbFolder);
        res.status(200).json({ pages });
    } catch (error) {
        console.error(error);
        res.status(500).json({ error: 'Conversion failed' });
    }
});

const isDirectRun = process.argv[1] && import.meta.url === pathToFileURL(process.argv[1]).href;
if (isDirectRun) {
    const port = 3030;
    const server = app.listen(port, () => {
        console.log(`PDF conversion service running on port ${port}`);
    });

    const shutdown = () => {
        server.close(() => process.exit(0));
    };
    process.on('SIGTERM', shutdown);
    process.on('SIGINT', shutdown);
}
