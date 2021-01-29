<?php

namespace App\Exports;

use App\Models\HollandTest;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class HollandRespuestasExport implements FromCollection, WithHeadings, ShouldAutoSize, WithTitle
{
    use Exportable;

    public $id_formulario;
    public $formulario;

    public function __construct($token)
    {
        $this->formulario = HollandTest::where('token', $token)->first();
        $this->id_formulario = $this->formulario->id;
    }

    public function collection()
    {
        $holland_respuestas = $this->getData();
        return $holland_respuestas;
    }

    public function title(): string
    {
        return substr('Holland_respuestas', 0, 31);
    }

    public function headings(): array
    {
        return collect($this->collection()->first())->keys()->toArray();
    }

    public function getData()
    {
        $holland_respuestas = HollandTest::leftJoin('holland_participante', 'holland_participante.test_id', 'holland_tests.id')
            ->where("holland_tests.id", $this->id_formulario)
            ->selectRaw("holland_participante.id,concat(holland_participante.nombres,' ',holland_participante.apellidos) as nombre_participante,
        holland_participante.correo,
        holland_participante.telefono,
        holland_participante.cedula,
        holland_participante.personalidad
        ", [])
            ->get();

        return $holland_respuestas;
    }
}
