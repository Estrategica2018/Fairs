<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CategorySeeder1 extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //

        $catAgend = new Category();
        $catAgend->type = 'ProductCategory';
        $catAgend->name = "Productos";
        $catAgend->fair_id = 1;
        $catAgend->resources = '{"color":"red"}';
        $catAgend->save();
        
        $catAgend = new Category();
        $catAgend->type = 'ProductCategory';
        $catAgend->name = "Servicios";
        $catAgend->fair_id = 1;
        $catAgend->resources = '{"color":"yellow"}';
        $catAgend->save();
        
    }
}
