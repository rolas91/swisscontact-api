<?php

namespace App\Exports;

use App\Functions\FormulariosDAL;
use App\Invoice;
use App\Models\Formulario;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class FormularioRespuestasExport implements FromCollection, WithHeadings, ShouldAutoSize, WithTitle
{
    use Exportable;

    public $id_formulario;
    public $formulario;
    public $id_centros;
    public $page;
    public $rowsPerPage;
    public $filtro;

    public function __construct($id_formulario = null, $id_centros = null, $page = 1, $rowsPerPage = 20, $filtro = '')
    {
        $this->id_formulario = $id_formulario;
        $this->formulario = Formulario::find($id_formulario);
        $this->id_centros = $id_centros;
        $this->page = $page;
        $this->rowsPerPage = $rowsPerPage;
        $this->filtro = $filtro;
    }

    public function collection()
    {
        $respuestas = FormulariosDAL::getRespuestasFormulario($this->id_formulario, $this->id_centros, $this->page, $this->rowsPerPage, $this->filtro, true);
        return $respuestas;
    }

    public function headings(): array
    {
        return collect($this->collection()->first())->keys()->toArray();
    }

    public function title(): string
    {
        return substr($this->formulario->nombre, 0, 31);
    }
}
