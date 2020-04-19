<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BRCitiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('cities')->insert([
            1, 'Afonso Cláudio', 'ES', 8]);
        DB::table('cities')->insert([
            2, 'Água Doce do Norte', 'ES', 8]);
        DB::table('cities')->insert([
            3, 'Águia Branca', 'ES', 8]);
        DB::table('cities')->insert([
            4, 'Alegre', 'ES', 8]);
    }
}
