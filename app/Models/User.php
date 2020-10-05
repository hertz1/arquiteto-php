<?php

namespace App\Models;

use Illuminate\Auth\GenericUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * App\Models\User
 *
 * @property string $uuid
 */
class User extends GenericUser
{
    use HasFactory;

    public function getAuthIdentifierName()
    {
        return 'uuid';
    }
}
