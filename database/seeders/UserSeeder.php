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
    }
}
