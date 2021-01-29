<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterSpRespuestasFormularios2 extends Migration
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
CREATE PROCEDURE `sp_respuestas_formulario`(in pId int )
        BEGIN
SET @sql = NULL;
SELECT
GROUP_CONCAT(DISTINCT
    CONCAT(
    'MAX(IF(fc.texto = ''',
    texto,
    ''', frc.valor, NULL)) AS ',
    get_column_name(texto)
    )
) INTO @sql

from formularios_respuestas_campos as frc 
inner join formularios_campos as fc on frc.id_formulario_campo = fc.id
where fc.id_formulario=pId;


SET @sql = CONCAT('select f.id as id_formulario,fr.id as id_respuesta,DATE_FORMAT(fr.fecha_inicio, \'%d/%m/%Y %l:%i:%s %p\') as fecha_inicio_formulario,DATE_FORMAT(fr.fecha_fin, \'%d/%m/%Y %l:%i:%s %p\') as fecha_fin_formulario,
case when f.id_modo = 5605 then \'Anónimo\' 
when p.nombres is not null then concat(p.nombres,\' \',p.apellidos) else fr.nombre_participante end as nombre_participante,centros.nombre as centro,cc.nombre as curso,fr.nota,',@sql,' 
from formularios_respuestas fr
inner join formularios f on fr.id_formulario = f.id
left join participantes p on fr.id_participante = p.id
left join centros on fr.id_centro = centros.id
left join cursos on fr.id_curso = cursos.id
left join catalogo_cursos cc on cc.id = cursos.id_curso
inner join formularios_respuestas_campos frc on fr.id = frc.id_formulario_respuesta 
inner join formularios_campos as fc on frc.id_formulario_campo = fc.id
where f.id=',pId,' and case when fr.id_participante is null then true else fr.id_centro in (',pCentros,') end
group by f.id,fr.id,DATE_FORMAT(fr.fecha_inicio, \'%d/%m/%Y %l:%i:%s %p\'),DATE_FORMAT(fr.fecha_fin, \'%d/%m/%Y %l:%i:%s %p\'),case when f.id_modo = 5605 then \'Anónimo\' 
when p.nombres is not null then concat(p.nombres,\' \',p.apellidos) else fr.nombre_participante end,fr.nota,centros.nombre,cc.nombre');

if(pId=1 or pId=5) then
set @sql = concat('select *,case when edad between 12 and 21 then \'12-21\'
    when edad between 22 and 31 then  \'22-31\'
    when edad between 32 and 41 then  \'32-41\'
    when edad between 42 and 51 then  \'42-51\'
    when edad between 52 and 61 then  \'52-61\'
    when edad between 62 and 71 then  \'62-71\'
    else \'>71\' end as rango_edad from (',@sql, ') as a');
end if;

set @sql = concat('select * from (',@sql,') as b order by b.id_respuesta desc');


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