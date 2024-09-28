<?php

use Illuminate\Database\Seeder;
use HttpOz\Roles\Models\Role;

class UsersRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		$adminRole = Role::create([
			'name' => 'Admin',
			'slug' => 'admin',
			'description' => '', // optional
			'group' => 'default', // optional, set as 'default' by default
		]);

		$moderatorRole = Role::create([
			'name' => 'Employee',
			'slug' => 'employee',
		]);
    }
}
