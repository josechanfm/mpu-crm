<?php

namespace App\Exports;

use App\Models\Entry;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class EntryExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    protected $form;

    function __construct($form)
    {
        $this->form = $form;
    }
    public function headings(): array
    {
        $columnHeaders = $this->form->fields->where('type','<>','html')->pluck('field_label')->orderBy('sequence')->toArray();
        return $columnHeaders;
    }
    public function collection()
    {
        return $this->form->excelRecords();
    }
}
