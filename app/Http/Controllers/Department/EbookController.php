<?php

namespace App\Http\Controllers\Department;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\File;
use RuntimeException;
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

        $file->move($bookPath, $filename);
        $this->ensureTemplate($this->templatePath.'book1', $bookPath);

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

    protected function convertUrl(): string
    {
        $parts = parse_url((string) config('app.url'));
        $host = $parts['host'] ?? '127.0.0.1';

        return 'http://'.$host.':3030/convert';
    }

    protected function convertPdf(string $thumbPath, string $pagePath, string $pdfPath): int
    {
        $ch = curl_init($this->convertUrl());
        curl_setopt_array($ch, [
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query([
                'thumb' => $thumbPath,
                'folder' => $pagePath,
                'pdf' => $pdfPath,
            ]),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 3600,
            CURLOPT_CONNECTTIMEOUT => 5,
        ]);
        $output = curl_exec($ch);
        $error = curl_error($ch);
        $status = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $json = json_decode($output ?: '', true);
        $pages = (int) ($json['pages'] ?? 0);
        if ($error || $status !== 200 || $pages < 1) {
            throw new RuntimeException($error ?: 'Flipbook conversion failed.');
        }
        return $pages;
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
