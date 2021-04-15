<?php

namespace Modules\Iprofile\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Modules\Iprofile\Entities\Role;


class RolePermissionsToAccessSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    Model::unguard();

    //Roles to set access
    $rolesToSetAccess = [
      'iadmin' => [
        'slugs' => ['admin', 'super-admin'],
        'permissions' => ['profile.api.login-iadmin']
      ],
      'ipanel' => [
        'slugs' => ['user', 'admin', 'super-admin'],
        'permissions' => ['profile.api.login-ipanel']
      ],
    ];

    //Set permissions
    foreach ($rolesToSetAccess as $roleAccess) {
      //Get roles
      $roles = Role::whereIn('slug', $roleAccess['slugs'])->get();
      //Set permission to each role
      foreach ($roles as $role) {
        //Get role permissions
        $rolePermissions = is_string($role->permissions) ? json_decode($role->permissions) : $role->permissions;
        //Set permissions
        foreach ($roleAccess['permissions'] as $permissionName) $rolePermissions[$permissionName] = true;
        //Update role permissions
        Role::where('id', $role->id)->update(['permissions' => $rolePermissions]);
      }
    }
  }
}