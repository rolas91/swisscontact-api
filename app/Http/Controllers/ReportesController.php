<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use App\Models\Centro;
use App\Models\Reporte;
use Illuminate\Http\Request;
use App\Models\UsuariosCentro;
use App\Models\CatalogosDetalle;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PhpParser\Node\Expr\Cast\Double;

class ReportesController extends Controller
{
    public function test(Request $requesr)
    {
        $cursos =  Curso::Join('centros', 'centros.id', 'cursos.id_centro')
            ->Join('cursos_matriculas as cm', 'cm.id_curso', 'cursos.id')
            ->join('catalogos_detalles as departamento', 'cm.id_departamento', 'departamento.id')
            ->join('catalogos_detalles as estado_civil', 'cm.id_estado_civil', 'estado_civil.id')
            ->join('catalogos_detalles as municipio', 'cm.id_municipio', 'municipio.id')
            ->join('catalogos_detalles as nivel_academico', 'cm.id_nivel_academico', 'nivel_academico.id')
            ->join('catalogos_detalles as pais', 'cm.id_pais', 'pais.id')
            ->join('catalogos_detalles as parentezco', 'cm.id_parentezco', 'parentezco.id')
            ->selectRaw("centros.id as id_centro,centros.nombre as centro, cursos.nombre as curso,cursos.descripcion,
        cursos.cupos,cm.correo,cm.nombres_participante,
        cm.apellidos_participante,
        case when cm.sexo = 'M' then 'Masculuno' else 'Femenino' end as sexo,
        DATE_FORMAT(cm.fecha_nacimiento, '%d/%m/%Y') as fecha_nacimiento,
        cm.edad,
        departamento.nombre as departamento,
		estado_civil.nombre as estado_civil,
		municipio.nombre as municipio,
		nivel_academico.nombre as nivel_academico
        
        ", [])->get();

        return response()->json(['cursos' =>  $cursos]);
    }

    public function DistribucionCentros()
    {
        $reporte = DB::select(DB::raw("select a.departamento as name,cantidad / (select count(1) from centros where deleted_at is null)*100 as y 
        from (
        select cd.nombre as departamento,count(1) as cantidad 
        from centros as c
        inner join catalogos_detalles as cd on c.id_departamento = cd.id
        where c.deleted_at is null
        group by cd.nombre
        ) as a order by cantidad desc;
        
        "));

        foreach ($reporte as $key => $value) {
            $reporte[$key]->y = (Double) $value->y;
            if ($reporte[$key]->name ==='Managua') {
                $reporte[$key]->sliced = true;
                $reporte[$key]->selected = true;
            }
        }
        return response()->json(['reporte' => $reporte], 200);
    }

    public function DistribucionCentrosXDepartamento()
    {
        $reporte = DB::select(DB::raw("select a.codigo, cantidad ,a.departamento as name,0 as seleccionado
        from (
        select cd.codigo, cd.nombre as departamento,count(c.id) as cantidad 
        from catalogos_detalles as cd
        left join centros as c on c.id_departamento = cd.id
        where cd.id_padre=1438
        group by cd.codigo,cd.nombre
        ) as a order by cantidad desc;
        "));

        foreach ($reporte as $key => $value) {
            $reporte[$key]->seleccionado = false;
        }

        return response()->json(['reporte' => $reporte], 200);
    }

    public function CentrosXDepartamento(Request $request)
    {

        $departamento = CatalogosDetalle::where(
            [
                ['id_padre',1438], //Pais Nicaragua
                ['codigo',$request['codigo']]
            ]
        )->firstOrFail();
        $centros = Centro::
            join('catalogos_detalles as departamento', 'centros.id_departamento', 'departamento.id')
            ->join('catalogos_detalles as municipio', 'centros.id_municipio', 'municipio.id')
            ->join('catalogos_detalles as pais', 'centros.id_pais', 'pais.id')

            ->where([
                ['deleted_at', '=', null],
                ['id_departamento', $departamento->id]
            ])
            ->whereRaw("centros.nombre like '%$request->filtro%'", [])
            ->selectRaw("centros.id,
					centros.nombre,
					centros.id_pais,
					centros.id_departamento,
					centros.id_municipio,
					centros.lema,
					centros.logo,
					centros.banner,
					centros.descripcion,
					centros.quienes_somos,
					centros.mision,
					centros.vision,
					centros.valores,
					centros.direccion,
					centros.latitud,
					centros.longitud,
					centros.contacto_nombre,
					centros.contacto_telefono,
					centros.contacto_correo,
					centros.telefono,
					centros.correo,
					centros.web_url,
					centros.facebook,
					centros.instagram,
					centros.twitter,
					centros.youtube,
					centros.computadoras,
					centros.tablets,
					centros.celulares,
					departamento.nombre as departamento,
					municipio.nombre as municipio,
					pais.nombre as pais", [])
            ->get();
       
        return response()->json(['centros'=> $centros], 200);
    }

    public function SetDatos(Request $request)
    {
        $usuario = $request->user();
        $id_centros = implode(",", UsuariosCentro::where('id_usuario', $usuario->id)->pluck('id_centro')->unique()->toArray());
                
        $datos =  DB::select(DB::raw("
        select cast(formularios.id as unsigned) as value, nombre as label 
        from formularios where deleted_at is null 
        and exists( select formularios_respuestas.id 
        from formularios_respuestas 
        inner join formularios on formularios_respuestas.id_formulario = formularios.id
        inner join usuarios on formularios.id_usuario_creacion = usuarios.id
        inner join usuarios_centros on formularios.id_usuario_creacion = usuarios_centros.id_usuario and usuarios_centros.id_centro in ($id_centros)
        where id_formulario = formularios.id

        )
            union all
        select 10000 as value, 'Centros' as label
            union all
        select 20000 as value, 'Cursos'  as label
            union all
        select 30000 as value, 'Instructores' as label
            union all
        select 40000 as value, 'Participantes'  as label 
        union all
        select 500000 as value, 'Tests de Holland' as label
        "));
        foreach($datos as $key=> $row){
            $datos[$key]->value = (int) $row->value; 
        }
        return response()->json([ 'datos' => $datos], 200);
    }

    public function store(Request $request)
    {
        //Validate inputs
        $reporte = $request["reporte"];
        Reporte::create([
            'id_datasource' => $request['id_datasource'],
            'nombre' => strtoupper($reporte['nombre']),
            'configuracion' => json_encode($reporte['esquema']),
            'id_usuario' => $request->user()->id
        ]);

        return response()->json(
            ['result' => true ],
            200
        );
    }

    public function index(Request $request)
    {
        $usuario = $request->user();
        $id_centros = implode(",", UsuariosCentro::where('id_usuario', $usuario->id)->pluck('id_centro')->unique()->toArray());
            
        $esquemas = Reporte::
        join('usuarios_centros', 'usuarios_centros.id_usuario', 'reportes.id_usuario')
        ->whereRaw("usuarios_centros.id_centro in ($id_centros) AND reportes.id_usuario=$usuario->id")
        ->selectRaw('reportes.id,id_datasource,nombre,configuracion as esquema ,reportes.id_usuario', [])->distinct()->get();
        return response()->json(['esquemas'=>$esquemas]);
    }

    public function update(Request $request)
    {
        $update = $request["reporte"];
        $reporte = Reporte::findOrFail($update["id"]);
        
        $reporte->id_datasource = $request['id_datasource'];
        $reporte->nombre =strtoupper($update['nombre']);
        $reporte->configuracion = json_encode($update['esquema']);
        $reporte->id_usuario = $request->user()->id;

        $reporte->save();
        return response()->json(
            ["result"=> true],
            201
        );
    }
}
