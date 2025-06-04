<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Ebook;

class EbookController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    
       return Inertia::render('EbookShelf',[
        'ebooks'=>Ebook::where('published',true)->orderBy('date_start','desc')->get()
       ]);
    }

}
