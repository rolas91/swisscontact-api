<?php

namespace App\Exports;

use App\Functions\CursosDAL;
use App\Models\Participante;
use App\Models\UsuariosCentro;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class CursosExport implements FromCollection, WithHeadings, ShouldAutoSize, WithTitle
{
    use Exportable;
    public $usuario, $filtro;

    public function __construct($usuario, $filtro)
    {
        $this->usuario = $usuario;
        $this->filtro = $filtro;
    }

    public function collection()
    {
        $usuario = $this->usuario;
        $filtro = $this->filtro;
        $dal = new CursosDAL($usuario, $filtro);
        return $dal->getAllCursos();
    }

    public function headings(): array
    {
        return collect($this->collection()->first())->keys()->toArray();
    }

    public function title(): string
    {
        return substr('cursos', 0, 31);
    }
}
