<?php

return [
  //Extra field to crud users
  'users' => [
    'jobTitle' => [
      'name' => 'jobTitle',
      'value' => '',
      'type' => 'input',
      'isFakeField' => true,
      'fakeFieldName' => 'settings',
      'columns' => 'col-12 col-md-6',
      'props' => [
        'label' => 'iprofile::common.crudFields.labelJobTitle',
      ],
    ],
    'jobRole' => [
      'name' => 'jobRole',
      'value' => '',
      'type' => 'input',
      'isFakeField' => true,
      'fakeFieldName' => 'settings',
      'columns' => 'col-12 col-md-6',
      'props' => [
        'label' => 'iprofile::common.crudFields.labelJobRole',
      ],
    ],
    'jobEmail' => [
      'name' => 'jobEmail',
      'value' => '',
      'type' => 'input',
      'isFakeField' => true,
      'fakeFieldName' => 'settings',
      'columns' => 'col-12 col-md-6',
      'props' => [
        'label' => 'iprofile::common.crudFields.labelJobEmail',
      ],
    ],
    'jobMobile' => [
      'name' => 'jobMobile',
      'value' => '',
      'type' => 'input',
      'isFakeField' => true,
      'fakeFieldName' => 'settings',
      'columns' => 'col-12 col-md-6',
      'props' => [
        'label' => 'iprofile::common.crudFields.labelJobMobile',
      ],
    ],
    'jobLinks' => [
      'name' => 'jobLinks',
      'value' => [],
      'type' => 'multiplier',
      'isFakeField' => true,
      'fakeFieldName' => 'settings',
      'columns' => 'col-12',
      'props' => [
        'label' => 'JobLink',
        'isDraggable' => true, // Default true
        'minQuantity' => 1,
        'maxQuantity'  => 10, // Default 10
        'fields' => [
          'linkIcon' => [
            'name' => 'linkIcon',
            'value' => null,
            'type' => 'selectIcon',
            'isFakeField' => true,
            'fakeFieldName' => 'settings',
            'columns' => 'col-12 col-md-3',
            'props' => [
              'label' => 'Icon',
            ],
          ],
          'linkLabel' => [
            'name' => 'linkLabel',
            'value' => '',
            'type' => 'input',
            'isFakeField' => true,
            'fakeFieldName' => 'settings',
            'columns' => 'col-12 col-md-3',
            'props' => [
              'label' => 'LinkLabel',
            ],
          ],
          'linkUrl' => [
            'name' => 'linkUrl',
            'value' => '',
            'type' => 'input',
            'isFakeField' => true,
            'fakeFieldName' => 'settings',
            'columns' => 'col-12 col-md-3',
            'props' => [
              'label' => 'linkUrl',
            ],
          ]

        ]
      ],
    ],
  ],
  //Extra field to crud departments
  'departments' => [],
  //Extra field to crud roles
  'roles' => []
];
