<?php

namespace Modules\Iprofile\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Isite\Entities\Layout;

class LayoutsProfileTableSeeder extends Seeder
{
  public function run()
  {
    $profiles = base_path().'/Modules/Iprofile/Resources/views/frontend/profile/layouts';
    $layoutsProfiles = scandir($profiles);
    $numLayout = 0;
    foreach ($layoutsProfiles as $layout) {
      if ($layout != '.' && $layout != '..') {
        $numLayout = $numLayout + 1;
        Layout::updateOrCreate(
          ['module_name' => 'Iprofile', 'entity_name' => 'User', 'system_name' => "{$layout}"],
          [
            'module_name' => 'Iprofile',
            'entity_name' => 'User',
            'is_internal' => '1',
            'path' => "iprofile::frontend.profile.layouts.{$layout}.index",
            'record_type' => 'master',
            'status' => '1',
            'system_name' => "{$layout}",
            'es' => [
              'title' => "Plantilla #{$numLayout} Para Perfil De Usuario",
            ],
            'en' => [
              'title' => "Template #{$numLayout} For User Profile",
            ],
          ]
        );
      }
    }
  }
}
