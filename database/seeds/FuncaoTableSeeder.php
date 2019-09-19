<?php

use Illuminate\Database\Seeder;

use Faker\Factory as Faker;

class FuncaoTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        foreach (range(1, 10) as $index) {
            DB::table('funcoes')->insert([
                'descricao' => $faker->jobTitle,
                'remuneracao' => random_int(0, 1500),
            ]);
        }
    }
}
