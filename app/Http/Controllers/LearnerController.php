<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LanguageMaterial;

class LearnerController extends Controller
{
    public function speaking(Request $request)
    {
        return inertia('Learner/Speaking',[
            'lang' => $request->input('lang','en-US'),
            'level'=> $request->input('level','A1'),
            'materials' => LanguageMaterial::where('type', 'speech')->get()
        ]);
    }

    public function getMaterial(Request $request)
    {
        $type=$request->input('type','speech');
        $lang=$request->input('lang','en-US');
        $level=$request->input('level','A1');
        $material = LanguageMaterial::where('lang',$lang)->where('type', $type)
            ->where('lang', $lang)
            ->where('level', $level)
            ->inRandomOrder()
            ->first();
        return response()->json($material);
    }
}
