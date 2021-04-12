<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CategorySeeder extends Seeder
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
        $catAgend->type = 'AgendaType';
		$catAgend->name = "Emprenderismo";
        $catAgend->fair_id = 1;
		$catAgend->resources = '{"color":"red"}';
        $catAgend->save();
		
		$catAgend = new Category();
        $catAgend->type = 'AgendaType';
		$catAgend->name = "Tecnología";
        $catAgend->fair_id = 1;
		$catAgend->resources = '{"color":"yellow"}';
        $catAgend->save();
		
		$catAgend = new Category();
        $catAgend->type = 'AgendaType';
		$catAgend->name = "Negocios";
        $catAgend->fair_id = 1;
		$catAgend->resources = '{"color":"pink"}';
        $catAgend->save();
    }
}
