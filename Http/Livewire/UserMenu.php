<?php

namespace Modules\Iprofile\Http\Livewire;

use Illuminate\Support\Facades\Route;
use Livewire\Component;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class UserMenu extends Component
{
  public $view;
  public $params;
  public $showLabel;
  public $moduleLinks;
  public $moduleLinksWithoutSession;
  public $onlyShowInTheDropdownHeader;
  public $onlyShowInTheMenuOfTheIndexProfilePage;
  public $ident;
  public $panel;
  public $profileRoute;
  public $openLoginInModal;
  public $openRegisterInModal;
  public $label;
  public $classUser;
  public $styleUser;


  public function mount($layout = 'user-menu-layout-1', $showLabel = false, $ident = "userMenuComponent",
                              $params = [], $openLoginInModal = false, $openRegisterInModal = false,
                              $onlyShowInTheDropdownHeader = true, $onlyShowInTheMenuOfTheIndexProfilePage = false,
                              $label = null, $classUser = '', $styleUser = '')
  {

    $this->view = 'iprofile::frontend.livewire.user-menu.layouts.' . (isset($layout) ? $layout : 'user-menu-layout-1') . '.index';

    $this->showLabel = $showLabel;
    $this->label = $label ?? trans('iprofile::frontend.button.my_account');
    $this->openLoginInModal = $openLoginInModal;
    $this->openRegisterInModal = $openRegisterInModal;
    $this->classUser = $classUser;
    $this->styleUser = $styleUser;
    $this->moduleLinks = [];
    $this->moduleLinksWithoutSession = [];
    $this->onlyShowInTheDropdownHeader = $onlyShowInTheDropdownHeader;
    $this->onlyShowInTheMenuOfTheIndexProfilePage = $onlyShowInTheMenuOfTheIndexProfilePage;
    $this->ident = $ident ?? "userMenuComponent";

  }

  public function reload($currentUrl){


    $modules = app('modules')->allEnabled();

    $this->moduleLinks = [];
    $this->moduleLinksWithoutSession = [];
    $locale = locale();
    $this->panel = config("asgard.iprofile.config.panel");

    if ($this->panel == "quasar") {
      $this->profileRoute = "/ipanel/#/me/profile/";
    } else {
      $this->profileRoute = \URL::route($locale . '.iprofile.account.index');
    }

    foreach ($modules as $name => $module) {
      $moduleLinksCfg = config('asgard.' . strtolower($name) . '.config.userMenuLinks');

      if (!empty($moduleLinksCfg)) {

        foreach ($moduleLinksCfg as &$moduleLink) {

          //Check if show or not de Link
          if (isset($moduleLink['activeBySettingName'])) {
            $checkSetting = setting($moduleLink['activeBySettingName']);
            if (!$checkSetting) {
              break;
            }
          }

          if (
            ($this->onlyShowInTheDropdownHeader && !isset($moduleLink["onlyShowInTheMenuOfTheIndexProfilePage"]))
            ||
            ($this->onlyShowInTheMenuOfTheIndexProfilePage && !isset($moduleLink["onlyShowInTheDropdownHeader"]))
          ) {

            if ($this->panel == "quasar" && isset($moduleLink['quasarUrl'])) {
              $moduleLink['url'] = $moduleLink['quasarUrl'] . "?redirectTo=" . $currentUrl["href"];
            } else
              if (!isset($moduleLink['url'])) {
                $routeWithLocale = $locale . '.' . $moduleLink['routeName'];
                if (Route::has($routeWithLocale))
                  $moduleLink['url'] = \URL::route($routeWithLocale);
                else if ($moduleLink['routeName'] && Route::has($moduleLink['routeName']))
                  $moduleLink['url'] = \URL::route($moduleLink['routeName']);
                else
                  $moduleLink['url'] = \URL::to('/');

              }

            if (isset($moduleLink["showInMenuWithoutSession"]) && $moduleLink["showInMenuWithoutSession"])
              $this->moduleLinksWithoutSession[] = $moduleLink;
            else
              $this->moduleLinks[] = $moduleLink;

          }

        }
      }
    }



  }

  private function makeParamsFunction()
  {

    return [
      "include" => $this->params["include"] ?? [],
      "take" => $this->params["take"] ?? 12,
      "page" => $this->params["page"] ?? false,
      "filter" => $this->params["filter"] ?? ["locale" => \App::getLocale()],
      "order" => $this->params["order"] ?? null,
    ];
  }

  public function logout()
  {
    $authProfileController = app("Modules\Iprofile\Http\Controllers\AuthProfileController");
    return $authProfileController->getLogout();
  }

  public function render()
  {
    $userData = null;
    $authApiController = app("Modules\Iprofile\Http\Controllers\Api\AuthApiController");
    if (\Auth::user()) {
      $user = $authApiController->me();
      $user = json_decode($user->getContent());
      $userData['data'] = $user->data->userData;
    }

    return view($this->view, ["user" => $userData]);
  }
}
