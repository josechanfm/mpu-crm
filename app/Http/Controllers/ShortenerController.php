<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shortener;

class ShortenerController extends Controller
{
    public function index(String $token, Request $request){
        $url=Shortener::where('token', $token)->first()?->url;
        if($url){
            return redirect()->away($url); // Assuming 'url' is the column name for the url link
        }
        return response()->json(['message' => 'Not Found'], 404);
    }
}
