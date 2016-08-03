<?php

use Illuminate\Database\Seeder;
use App\Models\Staff;
use App\Models\Role;

class StaffSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $admin = new Staff();
        $admin->username = 'admin';
        $admin->name = 'Administrator';
        $admin->password = Hash::make('123456');
        $admin->timezone = 'Asia/Shanghai';
        $admin->email = 'admin@domain.com';
        $admin->mobile = '18800000000';
        $admin->deleted_at = null;
        $admin->confirmed_at = \Carbon\Carbon::now();
        $admin->save();

        $admin->roles()->save(Role::find(1));
    }
}
