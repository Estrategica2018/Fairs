<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
         //\App\Models\User::factory(10)->create();
		//$this->call(FairSeeder::class);
        //$this->call(RoleSeeder::class);
        //$this->call(UserSeeder::class);
		//$this->call(StandTypesSeeder::class);
		//$this->call(CategorySeeder::class);
		//$this->call(CategorySeeder1::class);
		//$this->call(MerchantSeeder1::class);
		//$this->call(FairSeeder1::class);
		//$this->call(MerchantSeeder::class);
		//$this->call(SpeakerSeeder::class);
		//$this->call(UserAdminSeeder::class);
		$this->call(AdminSeeder1::class);
    }
}
