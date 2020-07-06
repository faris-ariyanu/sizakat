<?php

use Illuminate\Database\Seeder;
use App\Models\Menu;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $insert[] = [
    		"id" => "1",
    		"name" => "User Management",
    		"parent" => "0",
    		"icon" => "fa fa-user",
    		"link" => "#",
    		"sequence" => 20,
    	];
    	$insert[] = [
    		"id" => "2",
    		"name" => "Role",
    		"parent" => "1",
    		"icon" => "fa fa-list",
    		"link" => "/role",
    		"sequence" => 0,
    	];

    	$insert[] = [
    		"id" => "3",
    		"name" => "User",
    		"parent" => "1",
    		"icon" => "fa fa-list",
    		"link" => "/user",
    		"sequence" => 1,
    	];

    	$insert[] = [
    		"id" => "4",
    		"name" => "Menu",
    		"parent" => "1",
    		"icon" => "fa fa-list",
    		"link" => "/menu",
    		"sequence" => 2,
		];
		

		$insert[] = [
    		"id" => "5",
    		"name" => "Dashboard",
    		"parent" => "0",
    		"icon" => "fa fa-home",
    		"link" => "/dashboard",
    		"sequence" => 0,
    	];

        Menu::truncate();
        Menu::insert($insert);
    }
}
