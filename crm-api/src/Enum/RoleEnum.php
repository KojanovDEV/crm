<?php

declare(strict_types=1);

namespace App\Enum;

enum RoleEnum: string
{
    case ROLE_ADMIN = 'ROLE_ADMIN';
    case ROLE_USER = 'ROLE_USER';
    case ROLE_MIJOZ = 'ROLE_MIJOZ';
}
