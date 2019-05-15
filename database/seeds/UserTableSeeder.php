<?php

use Illuminate\Database\Seeder;
use App\User;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i=1; $i < 11 ; $i++) { 
        	User::create(
        		['name' => 'Usuario'.$i,
        		 'email' => 'usuario'.$i.'@gmail.com',
        		 'auth_token' => 'abcde',
        		 'password' => bcrypt('123456'),
        		]
        		);
        }
    }
}
