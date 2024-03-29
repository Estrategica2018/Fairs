<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\RoleUserFair;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserAdminSeeder extends Seeder
{
        /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = new User();
        $user->user_name = 'DavidCamacho';
        $user->name = 'David';
        $user->last_name = 'Camacho';
        $user->email = 'davithc01@gmail.com';
        $user->url_image = '';
        $user->contact = '{}';
        $user->password = Hash::make('12345678');
        $user->save();
		
		$user_rol_fair = new RoleUserFair();
        $user_rol_fair->user_id = $user->id;
        $user_rol_fair->role_id = 1;
        $user_rol_fair->fair_id = 4;
        $user_rol_fair->save();
		

		
    }
}
