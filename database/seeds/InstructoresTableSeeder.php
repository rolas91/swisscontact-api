<?php

use Illuminate\Database\Seeder;

class InstructoresTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        \DB::table('instructores')->delete();
    }
}
