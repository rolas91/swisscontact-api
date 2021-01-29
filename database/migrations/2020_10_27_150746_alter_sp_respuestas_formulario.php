<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterSpRespuestasFormulario extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $sp_sql =<<<SQL
DROP PROCEDURE IF EXISTS sp_respuestas_formulario;
CREATE PROCEDURE `sp_respuestas_formulario`(in `pPage` int, in `pRowsPerPage` int,IN `pId` INT, IN `pCentros` TEXT, in `pFiltro` TEXT)
BEGIN
SET GLOBAL group_concat_max_len = 100000;
SET SESSION group_concat_max_len = 100000;
    set @sql = NULL;
SELECT
    GROUP_CONCAT(DISTINCT
    CONCAT(
        'MAX(IF(fc.texto = ''',
        texto,
        ''', frc.valor, NULL)) AS ',
        get_column_name(texto, subtitulo)
    )
    ) INTO @sql
from formularios_respuestas_campos as frc
inner join formularios_campos as fc on frc.id_formulario_campo = fc.id
where fc.id_formulario=pId;

if (pFiltro is null ) then 
	set pFiltro = ''; 
end if;


SET @sql = CONCAT('select f.id as id_formulario,fr.id as id_respuesta,DATE_FORMAT(fr.fecha_inicio, \'%d/%m/%Y %l:%i:%s %p\') as fecha_inicio_formulario,DATE_FORMAT(fr.fecha_fin, \'%d/%m/%Y %l:%i:%s %p\') as fecha_fin_formulario,
case when f.id_modo = 5605 then \'Anónimo\'
when p.nombres is not null then concat(p.nombres,\' \',p.apellidos) else fr.nombre_participante end as nombre_participante,centros.nombre as centro,cc.nombre as curso,fr.nota,
case when p.id is not null then p.correo else null end as correo_participante,
    case when p.id is not null then p.telefono else null end as telefono_participante,
    case when p.id is not null then p.documento_identidad else null end as cedula_participante,
    case when p.id is not null then p.menor_edad else null end as participante_menor_edad,
    case when p.id is not null then p.fecha_nacimiento else null end as fecha_nacimiento_participante,
        case when p.id is not null then p.sexo else null end as sexo_participante,
        case when p.id is not null then p.salario else null end as salario_participante,
                    case when p.id is not null then (select cd.nombre from catalogos_detalles as cd where cd.id = p.id_estado_civil) else null end as estado_civil_participante,
                    case when p.id is not null then TIMESTAMPDIFF(YEAR, p.fecha_nacimiento, CURDATE()) else null end as _edad
                    ,count(1) as contador, cast(cursos.costo as unsigned) as costo,',@sql,'
                    
from formularios_respuestas fr
inner join formularios f on fr.id_formulario = f.id
left join participantes p on fr.id_participante = p.id
left join centros on fr.id_centro = centros.id
left join cursos on fr.id_curso = cursos.id
left join catalogo_cursos cc on cc.id = cursos.id_curso
inner join formularios_respuestas_campos frc on fr.id = frc.id_formulario_respuesta
inner join formularios_campos as fc on frc.id_formulario_campo = fc.id
where f.id=',pId,' and fr.deleted_at is null and case when fr.id_participante is null then true else fr.id_centro in (',pCentros,') end
and 

case when f.id_modo <> 5605 then 
	case when fr.id_participante is not null then  (concat(p.nombres,\' \',p.apellidos) like \'%',pFiltro,'%\' OR p.documento_identidad like \'%',pFiltro,'%\'  OR p.correo like \'%',pFiltro,'%\'  OR p.telefono like \'%',pFiltro,'%\' )
	else fr.nombre_participante like \'%',pFiltro,'%\' OR fr.correo_participante like \'%',pFiltro,'%\' end 
else true
end 
group by f.id,fr.id,DATE_FORMAT(fr.fecha_inicio, \'%d/%m/%Y %l:%i:%s %p\'),DATE_FORMAT(fr.fecha_fin, \'%d/%m/%Y %l:%i:%s %p\'),case when f.id_modo = 5605 then \'Anónimo\'
when p.nombres is not null then concat(p.nombres,\' \',p.apellidos) else fr.nombre_participante end,
centros.nombre,cc.nombre,fr.nota,
case when p.id is not null then p.correo else null end,
case when p.id is not null then p.telefono else null end,
case when p.id is not null then p.documento_identidad else null end,
case when p.id is not null then p.menor_edad else null end,
    case when p.id is not null then p.fecha_nacimiento else null end,
    case when p.id is not null then p.sexo else null end,
    case when p.id is not null then p.salario else null end,
    case when p.id is not null then (select cd.nombre from catalogos_detalles as cd where cd.id = p.id_estado_civil) else null end,
    case when p.id is not null then TIMESTAMPDIFF(YEAR, p.fecha_nacimiento, CURDATE()) else null end,
    cast(cursos.costo as unsigned)
    ');


 set @sql = concat('select *,case when _edad between 12 and 21 then \'12-21\'
    when _edad between 22 and 31 then  \'22-31\'
    when _edad between 32 and 41 then  \'32-41\'
    when _edad between 42 and 51 then  \'42-51\'
    when _edad between 52 and 61 then  \'52-61\'
    when _edad between 62 and 71 then  \'62-71\'
    else \'>71\' end as rango_edad from (',@sql, ') as a');


set @sql = concat('select *,(select count(1) from (',@sql,')x ) as total_rows from (',@sql,') as b order by b.id_respuesta desc limit ',pPage,',',pRowsPerPage);


PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;


END
SQL;
        DB::connection()->getPdo()->exec($sp_sql);

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $sql = "DROP PROCEDURE IF EXISTS sp_respuestas_formulario";
        DB::connection()->getPdo()->exec($sql);

    }
}
