<?php

use App\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Создадим административного пользователя.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Андрей',
            'email' => 'admin@aniart.com.ua',
            'password' => Hash::make('guN7]S)?Qf'),
            'group_id' => User::GROUP_ADMIN
        ]);
    }
}
