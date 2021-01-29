<?php

namespace App\Exports;


use App\Models\Bitacora;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class BitacoraExport implements FromCollection, WithHeadings, ShouldAutoSize, WithTitle
{
    use Exportable;
    public function collection()
    {
        $bitacora = Bitacora::join('usuarios', 'bitacora.user_id', 'usuarios.id')
            ->selectRaw("bitacora.id,bitacora.user_id,usuarios.nombre as usuario,bitacora.action,bitacora.model,bitacora.id_model,bitacora.ip_address,bitacora.user_agent,bitacora.url,bitacora.updated_at", [])
            ->get();
        return $bitacora;
    }

    public function headings(): array
    {
        return collect($this->collection()->first())->keys()->toArray();
    }

    public function title(): string
    {
        return substr('bitactora', 0, 31);
    }
}
