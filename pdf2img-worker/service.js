import express from 'express';
// import pdf2png   from 'pdf-poppler';
import {pdfToPng}  from 'pdf-to-png-converter';
import fs from 'fs';
import { dirname } from 'path';
import { fileURLToPath } from 'url';
import  bodyParser  from 'body-parser';


// 獲取當前目錄
const __dirname = dirname(fileURLToPath(import.meta.url));
const app = express();
app.use(express.json());
app.use(bodyParser.urlencoded({ extended: false }))
app.use(bodyParser.json())
app.get('', async (req, res) => {
    res.status(200).json({req:{params:req.params,query:req.query}});
})
app.post('/convert', async (req, res) => {
    const body=req.body
    const folder=body.folder
    const pdfPath=body.pdf
    const thumb_path=body.thumb
    try {
        if (!fs.existsSync(folder)) {
            fs.mkdirSync(folder);
        }
       await test_convert(pdfPath,folder )
       ///thumb
       if (!fs.existsSync(thumb_path)) {
            fs.mkdirSync(thumb_path);
        }
       await test_convert(pdfPath,thumb_path,0.5 )
        res.status(200).json({params:req.params,query:req.query,body:body});
    } catch (error) {
        console.log(error);
        res.status(500).json({outputPath:req.body,path:fs.existsSync(req.body.folder), msg:'error',error: 'Conversion failed' });
    }
});


async function  test_convert(check_path,folder,viewportScale=2){
            const pngPages = await pdfToPng(check_path, { // The function accepts PDF file path or a Buffer
                disableFontFace: true, // When `false`, fonts will be rendered using a built-in font renderer that constructs the glyphs with primitive path commands. Default value is true.
                useSystemFonts: false, // When `true`, fonts that aren't embedded in the PDF document will fallback to a system font. Default value is false.
                enableXfa: false, // Render Xfa forms if any. Default value is false.
                viewportScale: viewportScale, // The desired scale of PNG viewport. Default value is 1.0 which means to display page on the existing canvas with 100% scale.
                outputFolder: folder, // Folder to write output PNG files. If not specified, PNG output will be available only as a Buffer content, without saving to a file.
                strictPagesToProcess: false, // When `true`, will throw an error if specified page number in pagesToProcess is invalid, otherwise will skip invalid page. Default value is false.
                verbosityLevel: 0, // Verbosity level. ERRORS: 0, WARNINGS: 1, INFOS: 5. Default value is 0.
                outputFileMaskFunc: (pageNumber) => `${pageNumber}.jpg`,
            });
            return pngPages
}
const PORT = 3000;
const server=app.listen(PORT, () => {
    console.log(`PDF conversion service running on port ${PORT}`);
});


process.on('SIGTERM', () => {
    console.log('SIGTERM received. Shutting down gracefully...');
    server.close(() => {
        console.log('Server closed.');
        process.exit(0);
    });
});

process.on('SIGINT', () => { 
    console.log('SIGINT received. Shutting down gracefully...');
    server.close(() => {
        console.log('Server closed.');
        process.exit(0);
    });
});