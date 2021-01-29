<?php

namespace App\Exports;

use App\Functions\CentrosDAL;
use App\Functions\UsuariosDAL;
use App\Invoice;
use App\Models\Formulario;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class UsuariosExport implements FromCollection, WithHeadings, ShouldAutoSize, WithTitle
{
    use Exportable;
    public $usuario;
    public $filtro;

    public function __construct($usuario, $filtro)
    {
        $this->usuario = $usuario;
        $this->filtro = $filtro;
    }


    public function collection()
    {
        $usuario = $this->usuario;
        $filtro = $this->filtro;

        $dal = new UsuariosDAL($usuario, $filtro);
        return $dal->getAllUsuarios();
    }

    public function headings(): array
    {
        return
        collect($this->collection()->first())->keys()->toArray();
    }

    public function title(): string
    {
        return substr('usuarios', 0, 31);
    }
}
