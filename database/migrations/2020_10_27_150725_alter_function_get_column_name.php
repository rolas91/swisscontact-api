<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterFunctionGetColumnName extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $createfunction =<<<SQL
DROP function IF EXISTS `get_column_name`;
CREATE FUNCTION `get_column_name`(texto varchar(500),subtitulo varchar(500)) RETURNS varchar(200) CHARSET utf8mb4 DETERMINISTIC
BEGIN
DECLARE cad VARCHAR(500) DEFAULT '';
DECLARE str VARCHAR(500) DEFAULT '';
set str = texto;
if (subtitulo is not null ) then 
	set str = concat(texto,'_', subtitulo); 
end if;

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
set cad = replace(cad,'/','_');
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
set cad = replace(cad,'\\\','');
set cad = replace(cad,'@','');
set cad = replace(cad,'{','');
set cad = replace(cad,'}','');
set cad = replace(cad,'|','_');
set cad = replace(cad,'&','_');
set cad = replace(cad,'ˆ','');

return lower(substr(trim(cad),1,200));

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
