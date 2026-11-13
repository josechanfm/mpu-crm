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

// ─── Debug page ───────────────────────────────────────────────────────────
app.get('/', (_req, res) => {
    res.type('html').send(`<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>PDF worker debug</title>
<style>
body { font-family: ui-monospace, monospace; max-width: 56rem; margin: 2rem auto; padding: 0 1rem; line-height: 1.5; }
label { display: block; margin: .75rem 0 .25rem; font-weight: bold; }
input { width: 100%; padding: .4rem; font-family: inherit; font-size: .9rem; }
button { margin: .75rem .5rem .75rem 0; padding: .4rem .9rem; font-family: inherit; cursor: pointer; }
pre { background: #f5f5f5; padding: .75rem; overflow-x: auto; border-radius: 4px; font-size: .82rem; max-height: 30rem; }
.ok  { color: #0a7a32; font-weight: bold; }
.bad { color: #a40000; font-weight: bold; }
hr { border: none; border-top: 1px solid #ddd; margin: 1.5rem 0; }
</style>
</head>
<body>
<h1>PDF worker debug</h1>
<p>Worker cwd: <code>${process.cwd()}</code></p>

<label>PDF path (must be readable from inside this container)</label>
<input id="pdf" value="/var/www/html/public/flipbooks/Un91sWh/sprouting2025_small.pdf">

<label>Page output folder</label>
<input id="folder" value="/var/www/html/public/flipbooks/Un91sWh/files/mobile">

<label>Thumb output folder</label>
<input id="thumb" value="/var/www/html/public/flipbooks/Un91sWh/files/thumb">

<div>
  <button id="check">1. Check paths</button>
  <button id="convert">2. Run /convert</button>
</div>

<p id="status"></p>
<pre id="output"></pre>

<script>
const $ = (id) => document.getElementById(id);
const out = (msg) => { $('output').textContent = msg; };
const status = (msg, cls) => { $('status').className = cls; $('status').textContent = msg; };

function paths() {
  return {
    pdf:    $('pdf').value.trim(),
    folder: $('folder').value.trim(),
    thumb:  $('thumb').value.trim(),
  };
}

$('check').onclick = async () => {
  status('Checking…', '');
  const r = await fetch('/debug/check', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(paths()),
  });
  const j = await r.json();
  out(JSON.stringify(j, null, 2));
  status(j.allGood ? 'Paths look good.' : 'Problems found — see below.', j.allGood ? 'ok' : 'bad');
};

$('convert').onclick = async () => {
  status('Converting… (this can take a while)', '');
  out('');
  const p = paths();
  const body = new URLSearchParams({ pdf: p.pdf, folder: p.folder, thumb: p.thumb });
  const t0 = Date.now();
  try {
    const r = await fetch('/convert?debug=1', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body,
    });
    const text = await r.text();
    let j = null;
    try { j = JSON.parse(text); } catch (e) {}
    const ms = Date.now() - t0;
    if (r.ok && j && typeof j.pages === 'number') {
      status('Success: ' + j.pages + ' pages in ' + ms + 'ms', 'ok');
    } else {
      status('Failed (' + r.status + ') after ' + ms + 'ms', 'bad');
    }
    out(JSON.stringify(j ?? text, null, 2));
  } catch (err) {
    status('Request error: ' + err, 'bad');
  }
};
</script>
</body>
</html>`);
});

// ─── Debug helper: report filesystem state for the given paths ────────────
app.post('/debug/check', async (req, res) => {
    const { pdf, folder, thumb } = req.body || {};
    const check = async (p, wantWritable) => {
        if (!p) return { path: p, ok: false, error: 'empty path' };
        try {
            const stat = await fs.stat(p);
            const isDir = stat.isDirectory();
            if (wantWritable && isDir) {
                // Try to create + delete a temp file to prove writability
                const probe = path.join(p, '.probe-' + Date.now());
                await fs.writeFile(probe, '');
                await fs.unlink(probe);
            }
            return {
                path: p,
                ok: true,
                kind: isDir ? 'dir' : 'file',
                size: isDir ? null : stat.size,
                uid: stat.uid,
                gid: stat.gid,
                mode: '0' + (stat.mode & 0o777).toString(8),
            };
        } catch (e) {
            return { path: p, ok: false, error: e.code + ': ' + e.message };
        }
    };

    const pdfInfo = await check(pdf, false);
    // For output dirs: if they don't exist, try creating them (like renderBook does)
    const ensureDir = async (p) => {
        if (!p) return { path: p, ok: false, error: 'empty path' };
        try {
            await fs.mkdir(p, { recursive: true });
            return await check(p, true);
        } catch (e) {
            return { path: p, ok: false, error: e.code + ': ' + e.message };
        }
    };
    const folderInfo = await ensureDir(folder);
    const thumbInfo = await ensureDir(thumb);

    const allGood = pdfInfo.ok && folderInfo.ok && thumbInfo.ok;
    res.json({
        worker: {
            cwd: process.cwd(),
            uid: process.getuid ? process.getuid() : null,
            gid: process.getgid ? process.getgid() : null,
            node: process.version,
        },
        pdf: pdfInfo,
        folder: folderInfo,
        thumb: thumbInfo,
        allGood,
    });
});

// ─── Convert endpoint (debug=1 exposes the real error) ────────────────────
app.post('/convert', async (req, res) => {
    const pageFolder = req.body.folder;
    const pdfPath = req.body.pdf;
    const thumbFolder = req.body.thumb;
    const debug = req.query.debug === '1';
    try {
        const pages = await renderBook(pdfPath, pageFolder, thumbFolder);
        res.status(200).json({ pages });
    } catch (error) {
        console.error(error);
        if (debug) {
            res.status(500).json({
                error: 'Conversion failed',
                code: error?.code || null,
                message: error?.message || String(error),
                stack: (error?.stack || '').split('\n').slice(0, 8).join('\n'),
            });
        } else {
            res.status(500).json({ error: 'Conversion failed' });
        }
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
