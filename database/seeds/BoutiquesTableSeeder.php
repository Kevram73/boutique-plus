<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BoutiquesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('boutiques')->insert([
            [
                'id' => 1,
                'nom' => 'ZERO',
                'adresse' => 'ZERO',
                'telephone' => '0000',
                'contact' => '0000'
            ]
        ]);

    }
}
