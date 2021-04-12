<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //

        $user = new User();
        $user->user_name = 'Cristian01';
        $user->name = 'Cristian';
        $user->last_name = 'Jojoa';
        $user->email = 'cristianjojoa02@gmail.com';
        $user->url_image = 'image2';
        $user->contact = 'contact2';
        $user->password = Hash::make('123');
        $user->save();
		
		
		$user = new User();
        $user->user_name = 'MilenaRodriguez';
        $user->name = 'Milena';
        $user->last_name = 'Rodriguez';
        $user->email = 'milenaRodriguez@speaker.com';
        $user->url_image = 'https://image.freepik.com/vector-gratis/perfil-femenino-silueta_23-2147522231.jpg';
        $user->contact = '{}';
        $user->password = Hash::make('123');
        $user->save();
		
		
		$user = new User();
        $user->user_name = 'DianaB';
        $user->name = 'Diana';
        $user->last_name = 'Buenhombre';
        $user->email = 'cristianjojoa02@gmail.com';
        $user->url_image = 'https://i.pinimg.com/originals/40/32/e0/4032e0031e2e95989f1e76fe3d4f57b7.jpg';
        $user->contact = '{}';
        $user->password = Hash::make('123');
        $user->save();
		
		
		
		$user = new User();
        $user->user_name = 'Cristian01';
        $user->name = 'Cristian';
        $user->last_name = 'Jojoa';
        $user->email = 'cristianjojoa02@speaker.com';
        $user->url_image = 'image2';
        $user->contact = 'contact2';
        $user->password = Hash::make('123');
        $user->save();
		
		
		$user = new User();
        $user->user_name = 'IsaiasCoy';
        $user->name = 'Isaias';
        $user->last_name = 'Coy';
        $user->email = 'Coy@speaker.com';
        $user->url_image = 'https://images.vexels.com/media/users/3/140749/isolated/preview/4fb58265f9e1ad8d8dd7c35f06fa58d6-avatar-de-perfil-masculino-1-by-vexels.png';
        $user->contact = '{}';
        $user->password = Hash::make('123');
        $user->save();
    }
}
