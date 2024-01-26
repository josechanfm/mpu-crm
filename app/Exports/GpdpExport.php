<?php

namespace App\Exports;

use App\Models\Gpdp;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class GpdpExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    use Exportable;
    protected $gpdps;
    protected $headers;

    function __construct($gpdps, $headers)
    {
        $this->gpdps = $gpdps;
        $this->headers = $headers;
        // dd($this->headers);
    }
    public function headings(): array
    {
        return $this->headers;
    }

    public function collection()
    {
        return $this->gpdps;
    }
}
