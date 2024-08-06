<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Department;
use App\Models\Publication;

class PublicationController extends Controller
{
    public function get(Request $request){
        $this->validate($request,[
            'unit' => 'required',
            'cat'=>'sometimes|in:book,material,all',
            'id'=>'numeric'
        ]);
        
        $department=Department::where('abbr',strtoupper($request->unit))->first();
        $publications=Publication::whereBelongsTo($department);

        if($request->id){
            $publications->where('id',$request->id);
            return response()->json($publications->first());
        }
        if($request->cat && $request->cat!='all'){
            $publications->where('category',$request->cat);
        }
        return response()->json($publications->get());
    }
}
