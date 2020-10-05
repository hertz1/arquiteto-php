<?php

namespace App\Auth;

use Illuminate\Auth\GuardHelpers;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\UserProvider;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Signer\Hmac\Sha512;
use Lcobucci\JWT\Token;

class JwtGuard implements Guard
{
    use GuardHelpers;

    protected Token $token;

    public function __construct(string $jwt, UserProvider $provider)
    {
        $this->token = (new Parser())->parse($jwt);
        $this->provider = $provider;
    }

    public function user()
    {
        if (!is_null($this->user))
            return $this->user;

        if ($this->token && $this->verify()) {
            $id = $this->token->getClaim('sub');

            return $this->user = $this->provider->retrieveById($id);
        }
    }

    public function verify()
    {
        return $this->token->verify(new Sha512(), env('API_SECRET'));
    }

    public function validate(array $credentials = [])
    {
        //@TODO: Implementar integração com um serviço de autenticação
        return false;
    }
}
