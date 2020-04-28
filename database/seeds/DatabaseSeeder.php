<?php

use App\Role;
use App\User;
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
        Role::create([
            'id' => '1',
            'role' => 'Operator',
        ]);
        Role::create([
            'id' => '2',
            'role' => 'Admin',
        ]);
        Role::create([
            'id' => '3',
            'role' => 'Sender',
        ]);
        Role::create([
            'id' => '4',
            'role' => 'Receiver',
        ]);

        User::create([
            'name' => 'Iqbal',
            'email' => 'iqbal@naseebgoods.com',
            'user_name' => 'iqbal',
            'password' => Hash::make('password'),
            'role' => 2,
        ]);

    }
}
