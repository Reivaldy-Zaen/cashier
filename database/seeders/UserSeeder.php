<?php

    namespace Database\Seeders;

    use Illuminate\Database\Console\Seeds\WithoutModelEvents;
    use Illuminate\Database\Seeder;
    use Illuminate\Support\Facades\Hash;
    use App\Models\User;


    class UserSeeder extends Seeder
    {
        /**
         * Run the database seeds.
         */
        public function run(): void
        {
            //
            User::create([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'userId' => 'ADN-1',
            'password' => Hash::make('admin'),
            'role' => 'admin',
            ]);

            User::create([
            'name' => 'toko2',
            'email' => 'toko2@gmail.com',
            'userId' => 'ADN-2',
            'password' => Hash::make('toko2'),
            'role' => 'admin',
            ]);
        }
    }
