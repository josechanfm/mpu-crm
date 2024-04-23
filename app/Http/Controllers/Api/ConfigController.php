<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Config;

class ConfigController extends Controller
{
    public function Item(Request $request){
        $items=Config::where('key',$request->key)->get()->toArray();
        return response()->json(array_column($items,null,'lang'));
    }
}
