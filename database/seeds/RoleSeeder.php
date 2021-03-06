<?php

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Menu;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role = Role::firstOrCreate(
                    ['id' => '1'],
                    [
                        "code" => "ADM",
                        'name' => 'Super Admin',
                        'status' => 1
                    ]
                );
        
        $role->menus()->delete();
        foreach (Menu::all() as $menu) {
            $action = [];
            foreach($menu->action as $act){
                $action[$act] = 1;
            }

            $role->menus()->create([
                "role" => 1,
                "menu" => $menu->id,
                "permission" => json_encode($action)
            ]);
        }
    }
}
