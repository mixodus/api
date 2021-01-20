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
				'name' => 'Dashboard',
				'icon' => 'cil-speedometer',
				'url' => '/',
				'is_parent' => 0,
				'parent_id' => 0,
				'level' => 0,
				'status' => 1,
				'group_name' => 'home',
				'initial' => 'home-dashboard',
				'type' => 'link',
				'class' => 'super admin',
				'permissions' => ['view']
			],
			[
				'name' => 'Jobs',
				'url' => '/dashboard/jobs',
				'icon' => 'cil-laptop',
				'is_parent' => 0,
				'parent_id' => 0,
				'level' => 0,
				'status' => 1,
				'group_name' => 'jobs',
				'initial' => 'jobs',
				'type' => 'link',
				'class' => 'super admin',
				'permissions' => ['view', 'add', 'edit','delete']
			],
			[
				'name' => 'User Management',
				'icon' => 'cil-people',
				'url' => '/user-management',
				'is_parent' => 0,
				'parent_id' => 0,
				'level' => 0,
				'status' => 1,
				'group_name' => 'user-management',
				'initial' => 'user-management',
				'class' => 'super admin',
				'type' => 'collapsable',
				'permissions' => ['view']
			],
			[
				'name' => 'Admin List',
				'icon' => '-',
				'url' => '/dashboard/user-management/admin',
				'is_parent' => 0,
				'parent_id' => 0,
				'level' => 0,
				'status' => 1,
				'group_name' => 'user-management',
				'initial' => 'admin',
				'type' => 'item',
				'class' => 'super admin',
				'permissions' => ['view', 'add', 'edit','delete']
			],
			[
				'name' => 'Employee List',
				'icon' => '-',
				'url' => '/dashboard/user-management/employee',
				'is_parent' => 0,
				'parent_id' => 0,
				'level' => 0,
				'status' => 1,
				'group_name' => 'user-management',
				'initial' => 'employee',
				'type' => 'item',
				'class' => 'super admin',
				'permissions' => ['view', 'add', 'edit','delete']
			],
			[
				'name' => 'Employee Level',
				'icon' => '-',
				'url' => '/dashboard/user-management/employee-level',
				'is_parent' => 0,
				'parent_id' => 0,
				'level' => 0,
				'status' => 1,
				'group_name' => 'user-management',
				'initial' => 'level',
				'type' => 'item',
				'class' => 'super admin',
				'permissions' => ['view', 'add', 'edit','delete']
			],
			[
				'name' => 'Settings',
				'icon' => 'cil-applications-settings',
				'url' => '/settings/permissions',
				'is_parent' => 0,
				'parent_id' => 0,
				'level' => 0,
				'status' => 1,
				'group_name' => 'settings',
				'initial' => 'settings',
				'class' => 'super admin',
				'type' => 'collapsable',
				'permissions' => ['view']
			],
			[
				'name' => 'Access Role',
				'icon' => '-',
				'url' => '/dashboard/settings/roles',
				'is_parent' => 0,
				'parent_id' => 0,
				'level' => 0,
				'status' => 1,
				'group_name' => 'settings',
				'initial' => 'roles',
				'type' => 'item',
				'class' => 'super admin',
				'permissions' => ['view', 'add', 'edit', 'delete']
			],
		];
			// $menu_admin = [
			//     [
			//         'name' => 'Dashboard',
			//         'icon' => 'cil-speedometer',
			//         'url' => '/',
			//         'is_parent' => 0,
			//         'parent_id' => 0,
			//         'level' => 0,
			//         'status' => 1,
			//         'group_name' => 'home',
			//         'initial' => 'home-dashboard',
			//         'type' => 'link',
			//         'class' => 'super admin',
			//         'permissions' => ['view']
			//     ],
			//     [
			//         'name' => 'Jobs',
			//         'url' => '/dashboard/jobs',
			//         'icon' => 'cil-laptop',
			//         'is_parent' => 0,
			//         'parent_id' => 0,
			//         'level' => 0,
			//         'status' => 1,
			//         'group_name' => 'jobs',
			//         'initial' => 'jobs',
			//         'type' => 'link',
			//         'class' => 'super admin',
			//         'permissions' => ['view', 'add', 'edit','delete']
			//     ],
			//     [
			//         'name' => 'User Management',
			//         'icon' => 'cil-people',
			//         'url' => '/user-management',
			//         'is_parent' => 0,
			//         'parent_id' => 0,
			//         'level' => 0,
			//         'status' => 1,
			//         'group_name' => 'user-management',
			//         'initial' => 'user-management',
			//         'class' => 'super admin',
			//         'type' => 'collapsable',
			//     ],
			//     [
			//         'name' => 'Admin List',
			//         'icon' => '-',
			//         'url' => '/dashboard/user-management/admin',
			//         'is_parent' => 0,
			//         'parent_id' => 0,
			//         'level' => 0,
			//         'status' => 1,
			//         'group_name' => 'user-management',
			//         'initial' => 'admin',
			//         'type' => 'item',
			//         'class' => 'super admin',
			//         'permissions' => ['view', 'add', 'edit','delete']
			//     ],
			//     [
			//         'name' => 'Employee List',
			//         'icon' => '-',
			//         'url' => '/dashboard/user-management/employee',
			//         'is_parent' => 0,
			//         'parent_id' => 0,
			//         'level' => 0,
			//         'status' => 1,
			//         'group_name' => 'user-management',
			//         'initial' => 'employee',
			//         'type' => 'item',
			//         'class' => 'super admin',
			//         'permissions' => ['view', 'add', 'edit','delete']
			//     ],
			//     [
			//         'name' => 'Employee Level',
			//         'icon' => '-',
			//         'url' => '/dashboard/user-management/employee-level',
			//         'is_parent' => 0,
			//         'parent_id' => 0,
			//         'level' => 0,
			//         'status' => 1,
			//         'group_name' => 'user-management',
			//         'initial' => 'level',
			//         'type' => 'item',
			//         'class' => 'super admin',
			//         'permissions' => ['view', 'add', 'edit','delete']
			//     ],
			//     [
			//         'name' => 'Settings',
			//         'icon' => 'cil-applications-settings',
			//         'url' => '/settings/permissions',
			//         'is_parent' => 0,
			//         'parent_id' => 0,
			//         'level' => 0,
			//         'status' => 1,
			//         'group_name' => 'settings',
			//         'initial' => 'settings',
			//         'class' => 'super admin',
			//         'type' => 'collapsable',
			//     ],
			//     [
			//         'name' => 'Access Role',
			//         'icon' => '-',
			//         'url' => '/dashboard/settings/roles',
			//         'is_parent' => 0,
			//         'parent_id' => 0,
			//         'level' => 0,
			//         'status' => 1,
			//         'group_name' => 'settings',
			//         'initial' => 'roles',
			//         'type' => 'item',
			//         'class' => 'super admin',
			//         'permissions' => ['view', 'add', 'edit', 'delete']
			//     ],
			// ];

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
