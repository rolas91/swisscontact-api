<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrearFuncionGetColumnName extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $createfunction =<<<SQL
DROP FUNCTION IF EXISTS get_column_name;        
CREATE FUNCTION `competencias_para_ganar`.`get_column_name`(str varchar(500)) RETURNS varchar(50) CHARSET utf8mb4
BEGIN
DECLARE cad VARCHAR(500) DEFAULT '';

set cad = replace(trim(str),'?','');
set cad = replace(cad,' ','_');
set cad = replace(cad,'-','_');
set cad = replace(cad,'*','');
set cad = replace(cad,'¿','');
set cad = replace(cad,'é','e');
set cad = replace(cad,'á','a');
set cad = replace(cad,'í','i');
set cad = replace(cad,'ó','o');
set cad = replace(cad,'ú','u');
set cad = replace(cad,'Á','e');
set cad = replace(cad,'É','a');
set cad = replace(cad,'Í','i');
set cad = replace(cad,'Ó','o');
set cad = replace(cad,'Ú','u');
set cad = replace(cad,'ñ','n');
set cad = replace(cad,'Ñ','n');
set cad = replace(cad,'<','');
set cad = replace(cad,'>','');
set cad = replace(cad,'=','');
set cad = replace(cad,'/','');
set cad = replace(cad,'+','');
set cad = replace(cad,';','');
set cad = replace(cad,',','');
set cad = replace(cad,'.','');
set cad = replace(cad,'#','');
set cad = replace(cad,'$','');
set cad = replace(cad,'%','');
set cad = replace(cad,'(','');
set cad = replace(cad,')','');
set cad = replace(cad,'[','');
set cad = replace(cad,']','');
set cad = replace(cad,'`','');
set cad = replace(cad,'~','');
set cad = replace(cad,'  ','');
set cad = replace(cad,'''','');
set cad = replace(cad,':','');
set cad = replace(cad,';','');
set cad = replace(cad,'!','');
set cad = replace(cad,'\','');
set cad = replace(cad,'@','');
set cad = replace(cad,'{','');
set cad = replace(cad,'}','');
set cad = replace(cad,'|','');
set cad = replace(cad,'&','');
set cad = replace(cad,'ˆ','');


return lower(substr(trim(cad),1,50));

END
SQL;
        DB::connection()->getPdo()->exec($createfunction);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $sql = "DROP FUNCTION IF EXISTS get_column_name";
        DB::connection()->getPdo()->exec($sql);
    }
}
