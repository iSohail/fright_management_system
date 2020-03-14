<?php

use App\User;
use App\Role;
use App\Customer;
use App\Challan;
use App\Package;
use App\Bilty;

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
            'role' => 'Operator'
        ]);
        Role::create([
            'id' => '2',
            'role' => 'Admin'
        ]);
        Role::create([
            'id' => '3',
            'role' => 'Sender'
        ]);
        Role::create([
            'id' => '4',
            'role' => 'Receiver'
        ]);

        User::create([
            'id' => '1',
            'name' => 'Admin',
            'email' => 'admin@test.com',
            'user_name' => 'admin123',
            'password' => Hash::make('password'),
            'role' => 2
        ]);
        User::create([
            'id' => '2',
            'name' => 'Operator',
            'email' => 'operator@test.com',
            'user_name' => 'operator123',
            'password' => Hash::make('password'),
            'role' => 1
        ]);
        User::create([
            'id' => '3',
            'name' => 'Sender',
            'email' => 'sender@test.com',
            'user_name' => 'sender123',
            'password' => Hash::make('password'),
            'role' => 3
        ]);
        User::create([
            'id' => '4',
            'name' => 'Receiver',
            'email' => 'receiver@test.com',
            'user_name' => 'receiver123',
            'password' => Hash::make('password'),
            'role' => 4
        ]);

    }
}
