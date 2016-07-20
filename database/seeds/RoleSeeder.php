<?php
use App\Models\Role;
use Illuminate\Database\Seeder; 

class RoleSeeder extends Seeder {
    
    public function run() {
       $roles = [
           Role::NAME_ADMINISTRATOR
       ];
       
       foreach ($roles as $role) {
           $instance = new Role([
               'name' => $role,
               'display_name' => $role,
               'description' => $role
           ]);
           $instance->save();
       }
    }
    
}