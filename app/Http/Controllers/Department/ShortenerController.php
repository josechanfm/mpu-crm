<?php

namespace App\Http\Controllers\Department;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Shortener;

class ShortenerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $shorteners=Shortener::query();
        if ($request->has('search_column') && !is_null($request->search_column) && $request->has('search_text') && !is_null($request->search_text)) {
            $shorteners = $shorteners->where($request->search_column,'LIKE', '%'.$request->search_text.'%');
        }

        return Inertia::render('Department/Shorteners',[
            'links'=>$shorteners->paginate()
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
        //dd($request->all());
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'url' => 'required|url',
            'valid_at' => 'nullable|date',
            'expire_at' => 'nullable|date|after_or_equal:valid_at', // Ensure expire_at is after or equal to valid_at
            'remark' => 'nullable|string|max:500',
        ]);
        $validatedData['admin_user_id'] = auth()->user()->id;
        Shortener::create($validatedData);
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
    public function update(Shortener $shortener, Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'url' => 'required|url',
            'valid_at' => 'nullable|date',
            'expire_at' => 'nullable|date|after_or_equal:valid_at', // Ensure expire_at is after or equal to valid_at
            'remark' => 'nullable|string|max:500',
        ]);
        $shortener->update($request->all());
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Shortener $shortener)
    {
        dd($shortener);
    }
}
