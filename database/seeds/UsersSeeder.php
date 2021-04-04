<?php

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'first_name' => 'Henry',
            'last_name' => 'Ekwonwa',
            'middle_name' => 'Onyedikachi',
            'gender' => 'male',
            'phone' => '08125234436',
            'username' => 'creativeH',
            'email' => 'henimastic@gmail.com',
            'hash' => str_random(15),
            'password' => $password ?: $password = bcrypt('password'),
            'remember_token' => str_random(10),
            'confirmed' => true
        ]);

        DB::table('users')->insert([
            'first_name' => 'Deborah',
            'last_name' => 'Okubadejo',
            'middle_name' => 'Oluwatobi',
            'gender' => 'female',
            'phone' => '07013288699',
            'username' => 'cuted',
            'email' => 'cuted@gmail.com',
            'hash' => str_random(15),
            'password' => bcrypt('password'),
            'remember_token' => str_random(10),
            'confirmed' => true
        ]);

        DB::table('users')->insert([
            'first_name' => 'Crystal',
            'last_name' => 'Okubadejo',
            'middle_name' => 'Pelumi',
            'gender' => 'female',
            'phone' => '08125234437',
            'username' => 'crystal',
            'email' => 'pelumi@gmail.com',
            'hash' => str_random(15),
            'password' => bcrypt('password'),
            'remember_token' => str_random(10),
            'confirmed' => true
        ]);
        
        
        // User::truncate();

        // collect([
        //     [
        //         'first_name' => 'Henry',
        //         'last_name' => 'Ekwonwa',
        //         'middle_name' => 'Onyedikachi',
        //         'gender' => 'male',
        //         'phone' => '08125234436',
        //         'username' => 'creativeH',
        //         'email' => 'henimastic@gmail.com',
        //         'hash' => str_random(15),
        //         'password' => bcrypt('password'),
        //         'remember_token' => str_random(10),
        //         'confirmed' => true
        //     ],
        //     [
        //         'first_name' => 'Deborah',
        //         'last_name' => 'Okubadejo',
        //         'middle_name' => 'Oluwatobi',
        //         'gender' => 'female',
        //         'phone' => '07013288699',
        //         'username' => 'cuted',
        //         'email' => 'cuted@gmail.com',
        //         'hash' => str_random(15),
        //         'password' => bcrypt('password'),
        //         'remember_token' => str_random(10),
        //         'confirmed' => true
        //     ],
        //     [
        //         'first_name' => 'Crystal',
        //         'last_name' => 'Okubadejo',
        //         'middle_name' => 'Pelumi',
        //         'gender' => 'female',
        //         'phone' => '08125234437',
        //         'username' => 'crystal',
        //         'email' => 'pelumi@gmail.com',
        //         'hash' => str_random(15),
        //         'password' => bcrypt('password'),
        //         'remember_token' => str_random(10),
        //         'confirmed' => true
        //     ]
        // ])->each(function ($user) {
        //     factory(User::class)->create(
        //         [
        //             'first_name' => $user['first_name'],
        //             'last_name' => $user['last_name'],
        //             'middle_name' => $user['middle_name'],
        //             'gender' => $user['gender'],
        //             'phone' => $user['phone'],
        //             'username' => $user['username'],
        //             'email' => $user['email'],
        //             'hash' => $user['hash'],
        //             'password' => $user['password'],
        //             'remember_token' => $user['remember_token'],
        //             'confirmed' => $user['confirmed']
        //         ]
        //     );
        // });
    }
}
