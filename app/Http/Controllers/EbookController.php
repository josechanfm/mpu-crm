<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Imagick;
use Illuminate\Support\Facades\File;
use Exception;
use App\Models\Ebook;
use Illuminate\Support\Facades\Storage;
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
        $this->templatePath=storage_path('flipbooks/template/book1/');
        $this->destinationPath=public_path('flipbooks/');
    
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $ebook=Ebook::find(1);
       return Inertia::render('Ebook/List',[
        'ebooks'=>Ebook::all()
       ]);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return Inertia::render('Ebook/Create',[

        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $file = $request->file('file')[0];
        $data=[
            'original_filename'=>$file->getClientOriginalName(),
            'title'=>$request->title,
            'description'=>$request->description,
            'date_start'=>$request->date_start??date('y-m-d'),
            'data_end'=>$request->date_end,
            'remark'=>$request->remark,
        ];
        // //dd($file,$filename);
        $ebook=Ebook::create($data);
        $this->cloneTemplate($this->templatePath, $this->destinationPath, $ebook, $file);

        return redirect()->route('ebooks.index');
        //return redirect()->route('upload')->with('success', 'File uploaded and converted successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Ebook $ebook)
    {
        // dd($request->all(), $request->file(), $ebook);
        $data=[
            // 'original_filename'=>$file->getClientOriginalName(),
            'title'=>$request->title,
            'description'=>$request->description,
            'date_start'=>$request->date_start??date('y-m-d'),
            'data_end'=>$request->date_end,
            'remark'=>$request->remark,
        ];
        // //dd($file,$filename);
        $ebook->update($data);
        if($request->file('file')){
            $file = $request->file('file')[0];
            $ebook->original_filename=$file->getClientOriginalName();
            $ebook->save();
            $this->cloneTemplate($this->templatePath, $this->destinationPath, $ebook, $file);
        }
        return redirect()->route('ebooks.index');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function cloneTemplate($fromPath, $toPath, $ebook, $file){
        $toPath=$toPath.$ebook->uid;
        if(!is_dir($toPath)){
            try {
                File::copyDirectory($fromPath, $toPath);
                echo "Directory copy successfully";

            } catch (Exception $e) {
                echo "Error copying directory: " . $e->getMessage();
            }
        }else{
            File::cleanDirectory($toPath.'/files/mobile/');
            File::cleanDirectory($toPath.'/files/thumb/');
        }
        //dd($toPath,is_dir($toPath));
        $pdfPath = $file->move($toPath,$ebook->original_filename);
       
        $imagick = new Imagick();
        $imagick->setResolution(300, 300); // Set resolution for better quality
        $imagick->readImage($pdfPath);

        foreach ($imagick as $i => $image) {
            $image->setImageFormat('jpeg');
            $image->writeImage($toPath.'/files/mobile/'.($i+1).".jpg");
            $image->writeImage($toPath.'/files/thumb/'.($i+1).".jpg");
        }

        $filePath=$toPath.'/mobile/javascript/pages.js';
        $newContent="var total_page=".$imagick->getNumberImages()."; \n var book_title='".$ebook->title."'";
        if (file_put_contents($filePath, $newContent) === false) {
            die("Error writing to the file.");
        }
        $this->_generateQrCode($ebook);
        return true;
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
    public function _base62_encode($num){
        $alphabet = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
        $base = strlen($alphabet);
        $encoded = '';
    
        while ($num > 0) {
            $encoded = $alphabet[$num % $base] . $encoded;
            $num = floor($num / $base);
        }
    
        return $encoded;   
    }
}
