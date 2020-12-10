<?php

use Illuminate\Database\Seeder;
use App\Models\Dashboard\MenuModel;

class MenuTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
            $menu_admin = [
                [
                    'name' => 'Home',
                    'url' => '/',
                    'icon' => '',
                    'is_parent' => 0,
                    'parent_id' => 0,
                    'level' => 0,
                    'status' => 1,
                    'group_name' => 'home',
                    'initial' => null,
                    'type' => 'collapsable',
                    'class' => 'admin',
                    'permissions' => ['view']
                ],
                [
                    'name' => 'Dashboard',
                    'icon' => 'dashboard',
                    'url' => '/dashboard',
                    'is_parent' => 0,
                    'parent_id' => 1,
                    'level' => 1,
                    'status' => 1,
                    'group_name' => 'home',
                    'initial' => 'home-dashboard',
                    'type' => 'item',
                    'class' => 'super admin',
                    'permissions' => ['view']
                ],
                [
                    'name' => 'User Management',
                    'icon' => '',
                    'url' => '/user-management',
                    'is_parent' => 0,
                    'parent_id' => 0,
                    'level' => 0,
                    'status' => 1,
                    'group_name' => 'user',
                    'initial' => null,
                    'class' => 'super admin',
                    'type' => 'collapsable',
                ],
                [
                    'name' => 'Admin',
                    'icon' => 'people',
                    'url' => '/user-management/users-admin',
                    'is_parent' => 0,
                    'parent_id' => 2,
                    'level' => 1,
                    'status' => 1,
                    'group_name' => 'admin',
                    'initial' => 'admin',
                    'type' => 'item',
                    'class' => 'super admin',
                    'permissions' => ['view', 'add', 'edit','delete']
                ],
                [
                    'name' => 'Settings',
                    'icon' => '',
                    'url' => '/settings',
                    'is_parent' => 0,
                    'parent_id' => 0,
                    'level' => 0,
                    'status' => 1,
                    'group_name' => 'settings',
                    'initial' => null,
                    'class' => 'super admin',
                    'type' => 'collapsable',
                ],
                [
                    'name' => 'Access Role',
                    'icon' => 'accessibility',
                    'url' => '/setting/roles',
                    'is_parent' => 0,
                    'parent_id' => 3,
                    'level' => 1,
                    'status' => 1,
                    'group_name' => 'settings',
                    'initial' => 'setting-roles',
                    'type' => 'item',
                    'class' => 'super admin',
                    'permissions' => ['view', 'add', 'edit', 'delete']
                ],
            ];

            foreach ($menu_admin as $key => $value){
                $menu_id = $key + 1;
                $insert = MenuModel::updateOrCreate(
                    ['id' => $menu_id],
                    [
                        'name' => $value["name"],
                        'icon' => $value["icon"],
                        'url' => '/admin'.$value["url"],
                        'is_parent' => $value["is_parent"],
                        'parent_id' => $value["parent_id"],
                        'level' => $value["level"],
                        'status' => $value["status"],
                        'group_name' => $value["group_name"],
                        'initial' => $value["initial"],
                        'class' => $value["class"],
                        'type' => $value["type"]
                    ]
                );
                if ($value['initial'] != null){
                    foreach ($value['permissions'] as $k => $v){
                        $insert_permission = \App\Models\Dashboard\PermissionsModel::create(
                            [
                                'name'          => $value['initial']."-".$v,
                                'group_name'    => $value['initial'],
                                'action'        => $v,
                                'menu_id'       => isset($menu_id) ? $menu_id : null
                            ]
                        );
                    }
                }
            }
        }
}
