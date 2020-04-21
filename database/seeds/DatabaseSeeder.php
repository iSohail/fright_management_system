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
            'name' => 'Admin',
            'email' => 'admin@test.com',
            'user_name' => 'admin123',
            'password' => Hash::make('password'),
            'role' => 2
        ]);
        User::create([
            'name' => 'Operator',
            'email' => 'operator@test.com',
            'user_name' => 'operator123',
            'password' => Hash::make('password'),
            'role' => 1
        ]);
        User::create([
            'name' => 'Sender',
            'email' => 'sender@test.com',
            'user_name' => 'sender123',
            'password' => Hash::make('password'),
            'role' => 3
        ]);
        User::create([
            'name' => 'Receiver',
            'email' => 'receiver@test.com',
            'user_name' => 'receiver123',
            'password' => Hash::make('password'),
            'role' => 4
        ]);

        Customer::create([
            'customer_no' => '1000',
            'name' => 'c1',
            'company' => 'company1',
        ]);
        Customer::create([
            'customer_no' => '1001',
            'name' => 'c2',
            'company' => 'company2',
        ]);
        Customer::create([
            'customer_no' => '1002',
            'name' => 'c3',
            'company' => 'company3',
        ]);
        Customer::create([
            'customer_no' => '1003',
            'name' => 'c4',
            'company' => 'company4',
        ]);
        Customer::create([
            'customer_no' => '1004',
            'name' => 'c5',
            'company' => 'company5',
        ]);
        Customer::create([
            'customer_no' => '1005',
            'name' => 'c6',
            'company' => 'company6',
        ]);
        Customer::create([
            'customer_no' => '1006',
            'name' => 'c7',
            'company' => 'company7',
        ]);
        Customer::create([
            'customer_no' => '1007',
            'name' => 'c8',
            'company' => 'company8',
        ]);
        Customer::create([
            'customer_no' => '1008',
            'name' => 'c9',
            'company' => 'company9',
        ]);
        Customer::create([
            'customer_no' => '1009',
            'name' => 'c10',
            'company' => 'company10',
        ]);
        Customer::create([
            'customer_no' => '1010',
            'name' => 'c11',
            'company' => 'company11',
        ]);
        Customer::create([
            'customer_no' => '1011',
            'name' => 'c12',
            'company' => 'company12',
        ]);
        Customer::create([
            'customer_no' => '1012',
            'name' => 'c13',
            'company' => 'company13',
        ]);
        Customer::create([
            'customer_no' => '1013',
            'name' => 'c14',
            'company' => 'company14',
        ]);
        Customer::create([
            'customer_no' => '1014',
            'name' => 'c15',
            'company' => 'company15',
        ]);

    }
}
