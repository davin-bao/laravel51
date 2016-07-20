<?php

use Illuminate\Database\Seeder;
use App\Models\Staff;
use App\Models\Role;
use App\Models\Department;

class StaffSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create();

        $admin = new Staff();
        $admin->username = 'admin';
        $admin->name = 'Administrator';
        $admin->password = Hash::make('123456');
        $admin->email = 'admin@domain.com';
        $admin->mobile = '18800000000';
        $admin->deleted_at = null;
        $admin->confirmed_at = \Carbon\Carbon::now();
        $admin->save();

        $admin->roles()->save(Role::find(1));

        for ($i = 0; $i < 10; $i++) {
            $admin = new Staff();
            $admin->username = $faker->userName;
            $admin->password = Hash::make('123456');
            $admin->name = $faker->userName;
            $admin->email = $faker->safeEmail;
            $admin->mobile = '18800000000';
            $admin->deleted_at = null;
            $admin->confirmed_at = \Carbon\Carbon::now();
            $admin->save();

            $admin->roles()->save(Role::find(2));
        }
    }
}
