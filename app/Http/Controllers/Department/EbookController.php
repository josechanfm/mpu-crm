<?php

namespace App\Http\Controllers\Department;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\File;
use RuntimeException;
use Symfony\Component\Process\Process;
use App\Models\Ebook;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Label\Label;
use Endroid\QrCode\Logo\Logo;
use Endroid\QrCode\Writer\PngWriter;


class EbookController extends Controller
{
    protected $templatePath;
    protected $destinationPath;

    public function __construct() {
        $this->templatePath=storage_path('flipbooks/template/');
        $this->destinationPath=public_path('flipbooks/');
    
    }

    public function index()
    {
       return Inertia::render('Department/Ebook/List',[
        'ebooks'=>Ebook::orderBy('created_at','desc')->get()
       ]);
    }
    public function create()
    {
        return Inertia::render('Department/Ebook/Create',[

        ]);
    }


    public function store(Request $request)
    {
        ini_set('max_execution_time', 3600);

        $data=[
            'original_filename'=>'MPU eBook',
            'title'=>$request->title,
            'qrcode_title'=>$request->qrcode_title,
            'description'=>$request->description,
            'date_start'=>$request->date_start??date('Y-m-d'),
            'date_end'=>$request->date_end,
            'remark'=>$request->remark,
            'published'=>$request->published,
            'template'=>'book1'
        ];
        $ebook=Ebook::create($data);

        if($request->file('file')){
            $this->create_flip_book($ebook, $request->file('file')[0]);
        }
        $this->_generateQrCode($ebook);

        return redirect()->route('manage.ebooks.index');
    }
    public function update(Request $request, Ebook $ebook)
    {
        ini_set('max_execution_time', 3600);

        $data=[
            'title'=>$request->title,
            'qrcode_title'=>$request->qrcode_title,
            'description'=>$request->description,
            'date_start'=>$request->date_start??date('Y-m-d'),
            'date_end'=>$request->date_end,
            'remark'=>$request->remark,
            'published'=>$request->published,
            'template'=>'book1'
        ];
        $ebook->update($data);
        if($request->file('file')){
            $this->create_flip_book($ebook, $request->file('file')[0]);
        }
        $this->_generateQrCode($ebook);
        return redirect()->back();
        // return redirect()->route('manage.ebooks.index');
    }

    public function show($id)
    {
        dd($id);
    }

    function create_flip_book($ebook, $file){
        $bookPath = $this->destinationPath.$ebook->uid.'/';
        $filename = $this->pdfFilename($file);
        $ebook->original_filename = $filename;
        $ebook->save();

        $this->ensureTemplate($this->templatePath.'book1', $bookPath);
        $file->move($bookPath, $filename);

        $pages = $this->convertPdf(
            $bookPath.'files/thumb',
            $bookPath.'files/mobile',
            $bookPath.$filename
        );
        $this->writePagesJs($bookPath, $pages, $ebook->title);

        return true;
    }

    protected function pdfFilename($file): string
    {
        $name = basename(str_replace('\\', '/', $file->getClientOriginalName()));
        if (!str_ends_with(strtolower($name), '.pdf')) {
            $name .= '.pdf';
        }
        return $name !== '.pdf' ? $name : 'book.pdf';
    }

    protected function ensureTemplate(string $fromPath, string $toPath): void
    {
        if (!is_dir($toPath.'mobile')) {
            File::copyDirectory($fromPath, $toPath);
            return;
        }
        File::cleanDirectory($toPath.'files/mobile');
        File::cleanDirectory($toPath.'files/thumb');
    }

    protected function writePagesJs(string $bookPath, int $pages, ?string $title): void
    {
        $content = "var total_page={$pages};\nvar book_title=".json_encode($title ?: 'demo', JSON_UNESCAPED_UNICODE).';';
        File::put($bookPath.'mobile/javascript/pages.js', $content);
    }

    protected function convertPdf(string $thumbPath, string $pagePath, string $pdfPath): int
    {
        File::ensureDirectoryExists($pagePath);
        File::ensureDirectoryExists($thumbPath);

        $prefix = $pagePath.DIRECTORY_SEPARATOR.'page';
        $process = new Process([
            $this->pdftoppmBinary(),
            '-jpeg',
            '-r',
            '144',
            $pdfPath,
            $prefix,
        ]);
        $process->setTimeout(3600);
        $process->run();

        if (! $process->isSuccessful()) {
            throw new RuntimeException(trim($process->getErrorOutput()) ?: 'Flipbook conversion failed.');
        }

        $rendered = File::glob($prefix.'-*.jpg') ?: [];
        usort($rendered, fn (string $a, string $b) => $this->renderedPageNumber($a) <=> $this->renderedPageNumber($b));
        if ($rendered === []) {
            throw new RuntimeException(trim($process->getErrorOutput()) ?: 'Flipbook conversion produced no pages.');
        }

        $page = 0;
        foreach ($rendered as $source) {
            $page++;
            $pageFile = $pagePath.DIRECTORY_SEPARATOR.$page.'.jpg';
            File::move($source, $pageFile);
            $this->writeThumbnail($pageFile, $thumbPath.DIRECTORY_SEPARATOR.$page.'.jpg');
        }

        return $page;
    }

    protected function pdftoppmBinary(): string
    {
        $configured = (string) env('PDFTOPPM_PATH', '');
        if ($configured !== '' && is_file($configured)) {
            return $configured;
        }

        $bundled = base_path('bin/poppler/pdftoppm.exe');
        if (is_file($bundled)) {
            return $bundled;
        }

        throw new RuntimeException('pdftoppm was not found. Set PDFTOPPM_PATH or add bin/poppler/pdftoppm.exe.');
    }

    protected function renderedPageNumber(string $path): int
    {
        if (preg_match('/page-(\d+)\.jpg$/', str_replace('\\', '/', $path), $matches)) {
            return (int) $matches[1];
        }

        return 0;
    }

    protected function writeThumbnail(string $pageFile, string $thumbFile): void
    {
        $source = imagecreatefromjpeg($pageFile);
        if ($source === false) {
            throw new RuntimeException('Could not read a rendered page image.');
        }

        $width = imagesx($source);
        $height = imagesy($source);
        $thumbWidth = max(1, (int) round($width * 0.25));
        $thumbHeight = max(1, (int) round($height * 0.25));
        $thumb = imagecreatetruecolor($thumbWidth, $thumbHeight);
        imagecopyresampled($thumb, $source, 0, 0, 0, 0, $thumbWidth, $thumbHeight, $width, $height);
        imagejpeg($thumb, $thumbFile, 75);
        imagedestroy($thumb);
        imagedestroy($source);
    }

    public function _generateQrCode($ebook){
        // Create generic logo
        $logo = new Logo(
            path: public_path('images\mpu_logo.png'),
            resizeToWidth: 50,
            punchoutBackground: true
        );
        $label = new Label(
            text: $ebook->qrcode_title ?: $ebook->title ?: 'MPU',
            textColor: new Color(0,102,51)
        );
        
        $url=env('APP_URL').'/flipbooks/'.$ebook->uid;
        $qrcode=QrCode::create($url)->setSize(300)->setMargin(10);
        $writer=new PngWriter();
        $result=$writer->write($qrcode,$logo,$label);
        $filePath=$this->destinationPath.$ebook->uid;
        File::ensureDirectoryExists($filePath);
        file_put_contents($filePath.'/qrcode.png',$result->getString());
        return true;
    }

}
