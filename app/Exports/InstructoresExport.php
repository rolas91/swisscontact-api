<?php

namespace App\Exports;

use App\Functions\InstructoresDAL;
use App\Invoice;
use App\Models\Formulario;
use App\Models\Instructore;
use App\Models\UsuariosCentro;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class InstructoresExport implements FromCollection, WithHeadings, ShouldAutoSize, WithTitle
{
	use Exportable;
	private $user, $filtro;


	public function __construct($user, $filtro)
	{
		$this->user = $user;
		$this->filtro = $filtro;
	}


	public function collection()
	{
		$usuario = $this->user;
		$id_centros = UsuariosCentro::where('id_usuario', $usuario->id)->pluck('id_centro');
		$ids_usuarios = UsuariosCentro::whereIn('id_centro', $id_centros)->pluck('id_usuario');
		$dal = new InstructoresDAL($usuario, $this->filtro);
		return $dal->getAllInstructores();
	}

	public function headings(): array
	{
		return collect($this->collection()->first())->keys()->toArray();
	}

	public function title(): string
	{
		return substr('instructores', 0, 31);
	}
}
