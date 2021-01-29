<?php

namespace App\Exports;

use App\Functions\CentrosDAL;
use App\Invoice;
use App\Models\Formulario;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class CentrosExport implements FromCollection, WithHeadings, ShouldAutoSize, WithTitle
{
    use Exportable;


    public function collection()
    {
        return  CentrosDAL::getAllCentros();
    }

    public function headings(): array
    {
        return collect($this->collection()->first())->keys()->toArray();
    }

    public function title(): string
    {
        return substr('centros', 0, 31);
    }
}
