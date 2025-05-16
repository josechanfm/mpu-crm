<?php

namespace App\Http\Controllers\Department;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Department;
use App\Models\Form;
use App\Models\FormField;
use App\Models\EntryRecord;
use App\Models\Entry;
use Inertia\Inertia;
use App\Exports\EntryExport;
use Maatwebsite\Excel\Facades\Excel;
use PDF;
use Illuminate\Support\Str;

class EntryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Form $form)
    {
        //$form=Form::with('fields')->find($form->id);
        $entries = $form->tableEntries();
        return Inertia::render('Department/Form/Entries', [
            'organization' => session('organization'),
            'form' => $form,
            'entries' => $entries,
            'fields' => $form->fields,
            'entryColumns' => $form->entry_columns()
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
        //
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
    public function update(Request $request, Form $form, Entry $entry)
    {
        //
        collect($request->fields)->map(function ($entryField, $key) use ($entry) {
            if (is_array($entryField)) {
                if (isset($entryField['file'])) {
                    $file = $entryField['blob'];
                    $path = Storage::putFile('public/images/forms/photos', $file);
                    $entryField = Storage::url($path);
                }
            }
            $entry->records()->updateOrCreate(
                [
                    'form_field_id' => $key,
                ],
                [
                    'entry_id' => $entry->id,
                    //                'name_en' => $category['name_en'],
                    'form_field_id' => $key,
                    'field_value' => $entryField,
                ]
            );
        });
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Form $form, Entry $entry)
    {
        //  EntryRecord::where('entry_id', $id)->delete();
        //Entry::where('id', $id)->delete();
        $entry->delete();
        return Redirect()->back();
        //
    }

    public function export(Form $form)
    {
        dd($form->excelRecords());
        return Excel::download(new EntryExport($form), Str::slug($form->name).'.xlsx');
    }

    public function success(Form $form, Entry $entry, Request $request)
    {
        $entryRecords = EntryRecord::where('entry_id', $entry->id)->with('form_field')->get();
        $formFields = FormField::where('form_id', $form->id)->get();
        //dd($entryRecords);
        //dd($formFields);
        $table_data = [];
        if (strtoupper($request->format) == 'PDF') {
            if (!session('entryPdf') || session('entryPdf') != $entry->id) {
                return redirect()->route('manage.forms.index');
            }
            collect($formFields)->map(function ($field, $key) use ($entryRecords, &$table_data) {
                $entry_record = collect($entryRecords)->filter(function ($item) use ($field) {
                    return $item->form_field_id == $field->id;
                })->first();
                if ($field->type == 'radio') {
                    $value = array_filter(json_decode($field->options), function ($item) use ($entry_record) {
                        return $item->value === $entry_record?->field_value;
                    });
                    // dd($value);
                    $table_data[$field->field_label] = reset($value)->label ?? '';
                    // 
                } else if ($field->type == 'checkbox') {
                    $value = array_filter(json_decode($field->options), function ($item) use ($entry_record) {
                        return in_array($item->value, json_decode($entry_record->field_value));
                    });
                    $labels = [];
                    foreach ($value as $item) {
                        $labels[] = $item->label;
                    }
                    $result = implode(',', $labels);
                    $table_data[$field->field_label] = $result;
                } else {
                    $table_data[$field->field_label] = $entry_record?->field_value;
                };
            });
            //  dd($table_data);
            // return view('Form/EntrySuccess', [
            //     'table_data' => $table_data,
            // ]);
            $pdf = PDF::loadView('Form/EntrySuccess', [
                'table_data' => $table_data,
            ]);
            $pdf->render();
            
            return $pdf->stream('receipt.pdf', array('Attachment' => false));
        } else {
            // if (!session('entry') || session('entry') != $entry->id) {
            //     return redirect()->route('manage.form.entries.index',$form->id);
            // }
            //Session::flash('entryPdf', $entry->id);
            session()->flash('entryPdf', $entry->id);

            return Inertia::render('Department/Form/Success', [
                'form' => $form,
                'entry' => $entry,
                'entry_records' => $entryRecords,
            ]);
        }
    }
}
