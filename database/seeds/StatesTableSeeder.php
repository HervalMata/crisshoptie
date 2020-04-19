<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::unprepared("
            INSERT INTO 'states' VALUES ('Acre', 'AC');
            INSERT INTO 'states' VALUES ('Alagoas', 'AL');
            INSERT INTO 'states' VALUES ('Amazonas', 'AM');
            INSERT INTO 'states' VALUES ('Amapá', 'AP');
            INSERT INTO 'states' VALUES ('Bahia', 'BA');
            INSERT INTO 'states' VALUES ('Ceará', 'CE');
            INSERT INTO 'states' VALUES ('Distrito Federal', 'DF');
            INSERT INTO 'states' VALUES ('Espírito Santo', 'ES');
            INSERT INTO 'states' VALUES ('Goiás', 'GO');
            INSERT INTO 'states' VALUES ('Maranhão', 'MA');
            INSERT INTO 'states' VALUES ('Minas Gerais', 'MG');
            INSERT INTO 'states' VALUES ('Mato Grosso do Sul', 'MS');
            INSERT INTO 'states' VALUES ('Mato Grosso', 'MT');
            INSERT INTO 'states' VALUES ('Pará', 'PA');
            INSERT INTO 'states' VALUES ('Paraíba', 'PB');
            INSERT INTO 'states' VALUES ('Pernambuco', 'PE');
            INSERT INTO 'states' VALUES ('Piauí', 'PI');
            INSERT INTO 'states' VALUES ('Paraná', 'PR');
            INSERT INTO 'states' VALUES ('Rio de Janeiro', 'RJ');
            INSERT INTO 'states' VALUES ('Rio Grande do Norte', 'RN');
            INSERT INTO 'states' VALUES ('Rio Grande do Sul', 'RS');
            INSERT INTO 'states' VALUES ('Rio de Janeiro', 'RJ');
            INSERT INTO 'states' VALUES ('Rondônia', 'RO');
            INSERT INTO 'states' VALUES ('Roraima', 'RR');
            INSERT INTO 'states' VALUES ('Santa Catarina', 'SC');
            INSERT INTO 'states' VALUES ('Sergipe', 'SE');
            INSERT INTO 'states' VALUES ('São Paulo', 'SP');
            INSERT INTO 'states' VALUES ('Tocantins', 'TO');
        ");
    }
}
