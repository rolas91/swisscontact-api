<?php

namespace  App\Functions;

use App\Models\HollandTest;
use App\Models\UsuariosCentro;
use Illuminate\Support\Facades\DB;

class HollandDAL
{
    private $user;
    private $filtro;
    private $page;
    private $rowsPerPage;
    private $sortBy;
    private $descending;
    private $centro_filtro;
    

    public function __construct($user, $filtro, $page, $rowsPerPage, $sortBy, $descending, $centro_filtro='')
    {
        $this->user = $user;
        $this->filtro = $filtro;
        $this->page = $page;
        $this->rowsPerPage  = $rowsPerPage;
        $this->sortBy = $sortBy;
        $this->descending = $descending;
        $this->centro_filtro = $centro_filtro;
    }

    public function getAllTests()
    {
        $usuario = $this->user;
        $id_centros = implode(",", UsuariosCentro::where('id_usuario', $usuario->id)->pluck('id_centro')->unique()->toArray());

        $centro_filtro = $this->centro_filtro;


        $holland_tests = DB::table('holland_tests')
        ->join('usuarios', 'holland_tests.usuario_creacion', 'usuarios.id')
        ->join('usuarios_centros as ac', 'usuarios.id', 'ac.id_usuario')
        ->whereRaw("holland_tests.deleted_at is null  and  holland_tests.nombre like '%".$this->filtro."%' and ac.id_centro in ($id_centros) 
        and (usuarios.id_rol >= $usuario->id_rol or holland_tests.usuario_creacion = $usuario->id) $centro_filtro", [])
        ->selectRaw('holland_tests.id,
                    holland_tests.nombre,
                    (
                        select  group_concat(c.nombre SEPARATOR \', \') as centro 
                         from centros as c
                        inner join usuarios_centros as uc on c.id = uc.id_centro 
                        where uc.id_usuario = holland_tests.usuario_creacion
                        ) as centro,
					DATE_FORMAT(holland_tests.fecha_inicio, "%d/%m/%Y") as fecha_inicio,
					DATE_FORMAT(holland_tests.fecha_fin, "%d/%m/%Y") as fecha_fin,
                    holland_tests.usuario_creacion as id_usuario_creacion,
                    holland_tests.token,
                    (select count(1) from holland_respuesta where test_id = holland_tests.id) as respuestas,
                    usuarios.nombre as usuario_creacion', [])
                ->orderBy($this->sortBy, $this->descending)
                ->groupBy(DB::raw('holland_tests.id,holland_tests.nombre,DATE_FORMAT(holland_tests.fecha_inicio, "%d/%m/%Y"),
                DATE_FORMAT(holland_tests.fecha_fin, "%d/%m/%Y"),holland_tests.usuario_creacion, holland_tests.token,
                usuarios.nombre'))
               // ->toSql();
        ->paginate($this->rowsPerPage, ['*'], 'Page', $this->page);

        return $holland_tests;
    }
}
