<?php

namespace App\Exports;

use App\Functions\ParticipantesDAL;
use App\Models\Participante;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ParticipantesExport implements FromCollection, WithHeadings, ShouldAutoSize, WithTitle
{
	use Exportable;
	public function collection()
	{
		$dal =  ParticipantesDAL::getAllParticipantes();
		return $dal;
	}

	public function headings(): array
	{
		return collect($this->collection()->first())->keys()->toArray();
	}

	public function title(): string
	{
		return substr('participantes', 0, 31);
	}
}
