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

        //Customer
        Customer::create([
            'id' => '12345',
            'name' => 'Customer1',
            'cell_no' => '+923011233214',
            'customer_no' => 'c12345',
            'per_kg_rate' => '12',
            'per_cbm_rate' => '15',
            'per_pck_rate' => '20',
        ]);

        Bilty::create([
            'id' => '12345',
            'bilty_no' => 'b12345',
            'lg_bl_no' => '12345',
            'from' => 'Karachi',
            'to' => 'Lahore',
            'sender' => 'Rana',
            'receiver' => 'Saad',
            'receiver_address' => 'H NO 123 ABC RD XYZ CITY',
            'status' => 'registered',
            'payment_status' => 'paid',
            'bilty_charges' => 20,
            'local_charges' => 50,
        ]);
        
        Bilty::create([
            'id' => '12346',
            'bilty_no' => 'b12346',
            'lg_bl_no' => '12346',
            'from' => 'Karachi',
            'to' => 'Lahore',
            'sender' => 'Rana',
            'receiver' => 'Saad',
            'receiver_address' => 'H NO 123 ABC RD XYZ CITY',
            'status' => 'registered',
            'payment_status' => 'paid',
            'bilty_charges' => 20,
            'local_charges' => 50,
            'customer_id' => '12345'
        ]);

        Package::create([
            'id' => '12345',
            'bilty_id' => '12345',
            'package_no' => '12345',
            'description' => 'Rice',
            'unit' => 'kg',
            'quantity' => 20,
            'total_weight' => 50,
            'rent' => 2000,
            'labour' => 200,
        ]);

        Package::create([
            'id' => '12346',
            'bilty_id' => '12345',
            'package_no' => '12346',
            'description' => 'Flour',
            'unit' => 'kg',
            'quantity' => 20,
            'total_weight' => 50,
            'rent' => 2000,
            'labour' => 200,
        ]);

        Package::create([
            'id' => '12347',
            'bilty_id' => '12346',
            'package_no' => '12347',
            'description' => 'Daal',
            'unit' => 'kg',
            'quantity' => 20,
            'total_weight' => 50,
            'rent' => 2000,
            'labour' => 200,
        ]);
        
        Challan::create([
            'id' => '12345',
            'challan_no' => 'ch12345',
            'from' => 'Karachi',
            'to' => 'Lahore',
            'truck_no' => 't12345',
            'permit_no' => 'p12345',
            'transport' => 'transporting',
            'driver_name' => 'Hammad',
            'agent_name' => 'Maaz',
            'cnic' => '1234-2344234-3',
            'total_amount' => 10000,
            'expenses' => 5000,
            'grand_total' => 5000,
        ]);

       

    }
}
