<?php

use Illuminate\Database\Seeder;

class TypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Type::updateOrCreate(['name' => 'General']);
        \App\Type::updateOrCreate(['name' => 'Food']);
        \App\Type::updateOrCreate(['name' => 'Teaching']);
    }
}
