<?php

namespace App\Http\Controllers\Department\Dae;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Souvenir;

class SouvenirController extends Controller
{
    protected $filePath;
    protected $fileFullPath;

    public function __construct() {
        $this->filePath='/images/souvenirs/';
        $this->fileFullPath=public_path($this->filePath);
    
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //dd($request->all());
        if ($request->has('sort_column') && !is_null($request->sort_column)) {
            $souvenirs = Souvenir::orderBy($request->sort_column, $request->sort_order);
        }else{
            $souvenirs = Souvenir::orderBy('created_at', 'desc');
        }

        if ($request->has('search_column') && !is_null($request->search_column) && $request->has('search_text') && !is_null($request->search_text)) {
            $souvenirs = $souvenirs->where($request->search_column,'LIKE', '%'.$request->search_text.'%');
        }

        if ($request->has('show_all') && $request->show_all && $request->show_all == 'true') {
        }else{
           $souvenirs = $souvenirs->where('is_available', true);
        }

        // if(isset($souvenirs)){
        //     $souvenirs=$souvenirs->paginate($request->per_page?? 2);
        // }else{
        //     $souvenirs = Souvenir::paginate($request->per_page ?? 2);
        // }
        // dd($request->sort_column, $request->sort_order, $request->search_column, $request->search_text);
        return Inertia::render("Department/Dae/Souvenirs",[
            'souvenirs'=>$souvenirs=$souvenirs->paginate($request->per_page?? null),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name'=> 'required',
            'stock'=>'required',
            'price'=> 'required'
        ]);

        $souvenir=Souvenir::create($request->all());
        $path=$souvenir->id."/";
        $images=$souvenir->images ?? [];
        if($request->file('files')){
            foreach($request->file('files') as $file){
                //dd($file);
                $filename=$file['originFileObj']->getClientOriginalName();
                $file['originFileObj']->move($this->fileFullPath.$path, $filename);
                $images[] = $this->filePath.$path.$filename;
            }
        }
        $souvenir->images=$images;
        $souvenir->save();
        return redirect()->back();
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
    public function update(Request $request, Souvenir $souvenir)
    {
        //dd($request->all(), $request->file());
        $this->validate($request, [
            'name'=> 'required',
            'stock'=>'required',
            'price'=> 'required'
        ]);
        $data=$request->all();
        $path=$souvenir->id."/";
        $images=$souvenir->images ?? [];
        if($request->file('files')){
            foreach($request->file('files') as $file){
                //dd($file);
                $filename=$file['originFileObj']->getClientOriginalName();
                $file['originFileObj']->move($this->fileFullPath.$path, $filename);
                $images[] = $this->filePath.$path.$filename;
            }
        }
        $data['images']=$images;
        $souvenir->update($data);
        return redirect()->back();
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
    public function removeImage(Souvenir $souvenir, $imageId)
    {
        // Convert the images column to an array
        $images = $souvenir->images ?? [];
        // Check if the imageId exists in the images array
        //dd($images, $imageId, array_key_exists($imageId, $images));
        if(array_key_exists($imageId, $images)){
            if (file_exists(public_path($images[$imageId]))) {
                unlink(public_path($images[$imageId]));
            }
            unset($images[$imageId]);
            $souvenir->images = $images; // Re-index the array
            $souvenir->save();
            return response()->json([
                'message', 'Position template deleted successfully.',
                'images'=>$images
            ]);
        }
        return response()->json(['error' => 'Image not found.'], 404);
    }
}
