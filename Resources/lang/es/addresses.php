<?php

return [
  'list resource' => 'List addresses',
  'create resource' => 'Create addresses',
  'edit resource' => 'Edit addresses',
  'destroy resource' => 'Destroy addresses',
  'title' => [
    'addresses' => 'Direcciones',
    'create address' => 'Crear nueva dirección',
    'edit address' => 'Editar una dirección',
    'myAddresses' => 'Mis Direcciones',
    'important' => 'Importante'
  ],
  'button' => [
    'create address' => 'Crear una dirección',
    'add_address' => 'Agregar dirección',
    'cancel' => 'Cancelar'
  ],
  'table' => [
  ],
  'form' => [
    "name" => "Nombre",
    "firstName" => "Nombre",
    "lastName" => "Apellido",
    "receiver" => "Destinatario",
    "address1" => "Dirección",
    "address2" => "Dirección extra",
    "birthday" => "Fecha de Cumpleaños",
    "mainImage" => "Foto de perfil",
    "cellularPhone" => "Número de Teléfono",
    "telephone" => "Número de Teléfono",
    "address" => "Dirección",
    "phone" => "Teléfono",
    "city" => "Ciudad",
    "state" => "Provincia",
    "country" => "País",
    "select_country" => "Seleccione un País",
    "select_province" => "Seleccione una Provincia",
    "select_city" => "Seleccione una Ciudad",
    "select_option" => "Seleccione una Opción",
    "default" => "Dirección por defecto",
    "billing" => "Facturación",
    "shipping" => "Envío",
    "shippingAddress" => "Dirección de Envío",
    "billingAddress" => "Dirección de Facturación",
    "company" => "Nombre de la Compañía",
    "zipCode" => "Código Postal",
    'identification' => 'Tipo de Documento',
    'documentType' => 'Tipo de Documento',
    'documentNumber' => 'Número de Documento',
    'extraInfo' => 'Información Extra',
    'cantFindMyCity' => 'No encuentro mi ciudad',
    'customCityPlaceholder' => 'Escribe tu ciudad aquí',
    'email' => 'Correo Electronico'
  ],
  'messages' => [
    "created" => "Dirección creada exitosamente",
    "select address in searcher" => "Una vez que selecciones la dirección con el buscador, aparecerá un punto rojo en el mapa.",
    "not address in searcher" => "Si tu direccíon, no aparece en el buscador, deberás buscarla en el mapa y hacer click sobre el mismo para que aparezca el punto rojo (marcador)",
  ],
  'validation' => [
    "required" => "Valor requerido obligatoriamente",
    "documentNumber" => [
      "min" => "Mínimo 6 dígitos permitidos",
      "max" => "Máximo 10 dígitos permitidos"
    ],
    "first_name" => [
      "min" => "Mínimo 3 dígitos permitidos",
    ],
    "last_name" => [
      "min" => "Mínimo 3 dígitos permitidos",
    ],
    "address_1" => [
      "min" => "Mínimo 10 dígitos permitidos",
    ],
    "telephone" => [
      "min" => "Mínimo 6 dígitos permitidos",
      "max" => "Máximo 10 dígitos permitidos"
    ],
    'alerts' => [
      'invalid_data' => 'Información inválida',
      'missing_fields' => 'Faltan campos obligatorios, llena y envía nuevamente',
    ],
    'marker' => [
      'required' => '<span class="help-block text-danger">El marcador de la ubicación en el mapa es obligatorio</span>'
    ]
  ],
];
