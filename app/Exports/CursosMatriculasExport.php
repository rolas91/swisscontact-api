<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Functions\CursosMatriculasDAL;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class CursosMatriculasExport implements FromCollection, WithHeadings, ShouldAutoSize, WithTitle
{
    use Exportable;
    public $id_centros;
    public $filtro;
    public $filtro_curso;

    public function __construct($id_centros, $filtro, $filtro_curso)
    {
        $this->filtro =  $filtro;
        $this->id_centros  = $id_centros;
        $this->filtro_curso  = $filtro_curso;
    }

    public function collection()
    {
        $dal = new CursosMatriculasDAL($this->id_centros, $this->filtro, $this->filtro_curso);
        $inscripciones = $dal->getAllInscripciones();


        return $inscripciones->get();
    }

    public function headings(): array
    {

        return collect($this->collection()->first())->keys()->toArray();
    }

    public function title(): string
    {
        return substr('inscripciones', 0, 31);
    }
}
