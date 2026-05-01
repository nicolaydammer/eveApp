<?php

namespace App\Domain\Auth\Service;

use App\Domain\Auth\DTO\TokenData;
use App\Domain\Auth\DTO\VerifyOauthData;
use App\Domain\Auth\State\CharacterRepository;
use App\Domain\Infrastructure\Esi\Clients\SSOClient;
use App\Domain\Shared\User\UserRepository;
use Illuminate\Support\Facades\Auth;

class AuthService
{
    private SSOClient $SSOClient;

    private CharacterRepository $characterRepository;

    private UserRepository $userRepository;

    public function __construct(SSOClient $SSOClient, CharacterRepository $characterRepository, UserRepository $userRepository)
    {
        $this->SSOClient = $SSOClient;
        $this->characterRepository = $characterRepository;
        $this->userRepository = $userRepository;
    }

    public function getAuthorizationUrl(): string
    {
        return $this->SSOClient->getAuthorizationUrl();
    }

    public function exchangeCode(string $code): TokenData
    {
        return $this->SSOClient->exchangeCode($code);
    }

    public function verifyLogin(TokenData $tokenData): VerifyOauthData
    {
        return $this->SSOClient->verifyLogin($tokenData);
    }

    public function authenticateCharacter(VerifyOauthData $verifyOauthData)
    {
        $character = $this->characterRepository->find($verifyOauthData->CharacterID);

        // logged in, add it as alt, not logged in create new user and log in
        if (Auth::check()) {
            $user = Auth::user();

            if (is_null($character)) {
                $this->characterRepository->create($verifyOauthData, $user);
            }

            if ($character) {
                $this->characterRepository->update($verifyOauthData, $character);
            }
        } else {
            $user = null;

            if (is_null($character)) {

                $user = $this->userRepository->create($verifyOauthData->CharacterID);

                $this->characterRepository->create($verifyOauthData, $user);
            }

            if ($character) {
                $user = $character->get()->first()->user()->get()->first();

                $this->characterRepository->update($verifyOauthData, $character);
            }

            Auth::login($user);
        }
    }
}
