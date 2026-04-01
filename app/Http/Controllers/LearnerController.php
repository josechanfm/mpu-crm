<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LanguageMaterial;

class LearnerController extends Controller
{
    public function speaking()
    {
        return inertia('Learner/Speaking',[
            'materials' => LanguageMaterial::where('type', 'speech')->get()
        ]);
    }

    public function getMaterial(Request $request)
    {
        $type=$request->input('type','speech');
        $level = $request->input('level', 'A1');
        $material = LanguageMaterial::where('type', $type)
            ->where('level', $level)
            ->inRandomOrder()
            ->first();
        return response()->json($material);
    }
}
