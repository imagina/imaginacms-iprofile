<?php

namespace Modules\Iprofile\Transformers;

use Modules\Core\Icrud\Transformers\CrudResource;
use Modules\Iforms\Transformers\FormTransformer;
use Modules\Iprofile\Transformers\SettingTransformer;

class RoleTransformer extends CrudResource
{
  /**
   * Method to merge values with response
   *
   * @return array
   */
  public function modelAttributes($request)
  {
    $roleRepository = app("Modules\Iprofile\Repositories\RoleApiRepository");
    $role = $roleRepository->getItem($this->id);
    //Get settings
    $settingsResponse = [];
    if($role) {
      $settings = $role->settings()->get();
      $settings = json_decode(json_encode(SettingTransformer::collection($settings)));
      foreach ($settings as $setting) $settingsResponse[$setting->name] = $setting->value;
    }

    // esta sección de código se agregó porque el formeable empezó a dar problemas cuando se implementó el tenant
    // la idea es que los formularios pertenezcan a un tenant también pero en el caso del formulario que pertenece al
    // registro que es un formulario central no llega con el trait porque el trait utiliza una relación morphMany que
    // ejecuta el scope del Tenant y no hay forma de evitarlo, por eso se realizó esta adaptación: se busca el id del
    // formulario asociado en la tabla formeable y luego se busca el objeto completo pero a través del repositorio
    $formRepository = app("Modules\Iforms\Repositories\FormRepository");
    $formeable = \DB::table("iforms__formeable")
      ->where("formeable_type", "Modules\\Iprofile\\Entities\\Role")
      ->where("formeable_id", $this->id)
      ->first();

    $form = isset($formeable->form_id) ? $formRepository->getItem($formeable->form_id) : null;

    return [
      'permissions' => $this->permissions ?? (object)[],
      'settings' => (object)$settingsResponse,
      'form' => isset($form->id) ? new FormTransformer($form) : null,
      'formId' => $form->id ?? null,
    ];
  }
}
