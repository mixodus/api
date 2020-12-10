<?php

use Illuminate\Database\Seeder;
use App\Models\Dashboard\RolesHasPermissions;

class RolesPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //give all access to super admin
        $menus = \DB::table('xin_menus')
            ->select('id')
            ->where('class', 'super admin')
            ->get();
        foreach ($menus as $menu){

            $permission = \DB::table('xin_permissions')
                ->select('id')
                ->where('menu_id', $menu->id)
                ->get();

            if($permission){
                foreach ($permission as $item) {
                    \DB::table('xin_roles_permissions')->insert([
                        'role_id' => 1,
                        'permission_id' => $item->id,
                    ]);
                }
            }

        }
    }
}
