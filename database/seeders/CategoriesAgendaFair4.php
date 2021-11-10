<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CategoriesAgendaFair4 extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $catAgend = new Category();
        $catAgend->type = 'AgendaType';
        $catAgend->name = "Productos";
        $catAgend->fair_id = 4;
        $catAgend->resources = '{"color":"red"}';
        $catAgend->save();

        $catAgend = new Category();
        $catAgend->type = 'AgendaType';
        $catAgend->name = "Servicios";
        $catAgend->fair_id = 4;
        $catAgend->resources = '{"color":"green"}';
        $catAgend->save();
    }
}
