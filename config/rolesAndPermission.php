<?php

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

return [
    
    'roles' => [
        'admin' => 'Administrator',
        'tutor' => 'Tutor',
    ],

    'permissions' => [
        'create_student' => 'Create student',
        'edit_student' => 'Edit_student',
    ],
];
