<?php

/**
 * Provisiona uma instância de usuário simples para a aplicação.
 * Como a aplicação não possui dados de usuário e não há uma integração
 * com um serviço de usuários, uma simples instância do Model é retornada.
 */

namespace App\Auth;

use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;

class AuthUserProvider implements UserProvider
{
    public function retrieveById($identifier)
    {
        return new User([
            'uuid' => $identifier
        ]);
    }

    public function retrieveByToken($identifier, $token)
    {
        return new User([
            'uuid'  => $identifier,
            'token' => $token
        ]);
    }

    public function updateRememberToken(Authenticatable $user, $token)
    {
        //@TODO: Implementar integração com um serviço de autenticação
    }

    public function retrieveByCredentials(array $credentials)
    {
        //@TODO: Implementar integração com um serviço de autenticação
    }

    public function validateCredentials(Authenticatable $user, array $credentials)
    {
        //@TODO: Implementar integração com um serviço de autenticação
    }
}
