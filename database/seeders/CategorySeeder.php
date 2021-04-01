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
        $catAgend->name = 'AgendaType';
        $catAgend->description = '';
        $catAgend->resources = '';
		$catAgend->fair_id = 1;
        $catAgend->save();
		$categories = [ "Emprenderismo", "Negocios", "Tecnología", "Otros"];
		foreach ($categories as $name){
			$catAg1 = new Category();
			$catAg1->name = $name;
			$catAg1->category_id = $catAgend->id;
			$catAg1->description = '';
			$catAg1->resources = '';
			$catAg1->save();			
		}
    }
}
