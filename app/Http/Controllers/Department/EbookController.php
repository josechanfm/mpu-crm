<?php

namespace App\Http\Controllers\Department;

use App\Http\Controllers\Controller;
use App\Models\Member;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\File;
use Exception;
use Smalot\PdfParser\Parser as Parser;
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
        $time=ini_get('max_execution_time');
        ini_set('max_execution_time',3600);

        $data=[
            'original_filename'=>'MPU eBook',
            'title'=>$request->title,
            'description'=>$request->description,
            'date_start'=>$request->date_start??date('y-m-d'),
            'data_end'=>$request->date_end,
            'remark'=>$request->remark,
            'published'=>$request->published,
            'template'=>'book1'
        ];
        // //dd($file,$filename);
        $ebook=Ebook::create($data);

        if($request->file('file')){
            $file = $request->file('file')[0];
            $ebook->original_filename=$file->getClientOriginalName();
            $ebook->save();
            $this->create_flip_book($ebook, $file);
        }
        $this->_generateQrCode($ebook);

        return redirect()->route('manage.ebooks.index');
    }
    public function update(Request $request, Ebook $ebook)
    {
        $time=ini_get('max_execution_time');
        ini_set('max_execution_time',3600);

        $data=[
            'title'=>$request->title,
            'description'=>$request->description,
            'date_start'=>$request->date_start??date('y-m-d'),
            'data_end'=>$request->date_end,
            'remark'=>$request->remark,
            'published'=>$request->published,
            'template'=>'book1'
        ];
        //dd($request->all(), $data);
        $ebook->update($data);
        if($request->file('file')){
            $file = $request->file('file')[0];
            $ebook->original_filename=$file->getClientOriginalName();
            $ebook->save();
            $this->create_flip_book($ebook, $file);
        }
        return redirect()->back();
        // return redirect()->route('manage.ebooks.index');
    }

    public function show($id)
    {
        dd($id);
    }

    function create_flip_book($ebook, $file){
        $book_path=$this->destinationPath.$ebook->uid.'/';
        $book_img_path=$book_path."files/mobile";
        $thumb_img_path=$book_path."files/thumb";
        //$template_path=base_path()."/resources/ebookTemplate/book1";
        $template_path=$this->templatePath.'book1';
        $file_path=$book_path.$ebook->original_filename;
        
        // dd($book_path, $book_img_path, $thumb_img_path, $template_path, $file_path, $ebook->original_filename);

        $pdfPath = $file->move($book_path, $ebook->original_filename);
        $pageNum=$this->countPdfPages($book_path.$ebook->original_filename);
        $this->cloneTemplate($template_path ,$book_path,$pageNum);
        $d= $this->_remote_post( $thumb_img_path, $book_img_path, $file_path);
        return true;
    }

    function _remote_post($thumbFullPath='',$folderFullPath='', $pdfFullPath='' ){///
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,"127.0.0.1:3030/convert");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,
                    "thumb={$thumbFullPath}&folder={$folderFullPath}&pdf={$pdfFullPath}");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $server_output = curl_exec ($ch);
        curl_close ($ch);
        dd($server_output);
        return ($server_output) ;
    }

     public function cloneTemplate($fromPath, $toPath,$pageNum){
        //dd($fromPath, $toPath, $pageNum, !is_dir($toPath));
        if(!is_dir($toPath)){
            try {
                File::copyDirectory($fromPath, $toPath);
                echo "Directory copy successfully";

            } catch (Exception $e) {
                echo "Error copying directory: " . $e->getMessage();
            }
        }else{
            File::cleanDirectory($toPath.'files/mobile/');
            File::cleanDirectory($toPath.'files/thumb/');
            File::copyDirectory($fromPath, $toPath);
        }
        $filePath=$toPath.'mobile/javascript/pages.js';
        $newContent="var total_page={$pageNum}; \nvar book_title='demo';";
        if (file_put_contents($filePath, $newContent) === false) {
            die("Error writing to the file.");
        }
        return true;
    }
    ///
        function countPdfPages($filename) {
            try {
                $parser = new Parser();
                $pdf    = $parser->parseFile($filename);
                $pages  = $pdf->getPages();
                $pageCount = count($pages);
                return $pageCount;
            } catch (Exception $e) {
                return 100;
            }
        }

    public function _generateQrCode($ebook){
        // Create generic logo
        $logo = new Logo(
            path: public_path('images/mpu_logo.png'),
            resizeToWidth: 50,
            punchoutBackground: true
        );
        $label = new Label(
            text: $ebook->title,
            textColor: new Color(0,102,51)
        );
        $url=env('APP_URL').'/flipbooks/'.$ebook->uid;
        $qrcode=QrCode::create($url)->setSize(300)->setMargin(10);
        $writer=new PngWriter();
        $result=$writer->write($qrcode,$logo,$label);
        $filePath=$this->destinationPath.$ebook->uid;
        file_put_contents($filePath.'/qrcode.png',$result->getString());
        return true;
    }

}
