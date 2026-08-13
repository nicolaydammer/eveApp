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

    public function authenticateCharacter(VerifyOauthData $verifyOauthData): void
    {
        $character = $this->characterRepository->find(
            $verifyOauthData->CharacterID
        );

        /*
     * Already authenticated:
     *
     * The authenticated user is trusted, so an existing character
     * can be added to or transferred to this user.
     */
        if (Auth::check()) {
            $user = Auth::user();

            if (is_null($character)) {
                $this->characterRepository->create(
                    $verifyOauthData,
                    $user
                );

                return;
            }

            $this->characterRepository->update(
                $verifyOauthData,
                $character,
                $user
            );

            return;
        }

        /*
     * Not authenticated:
     *
     * A character can only establish a session if it is the
     * main character of its existing user.
     */
        if (is_null($character)) {
            $isFirstUser = ! $this->userRepository->hasUsers();

            $user = $this->userRepository->create(
                $verifyOauthData->CharacterID
            );

            if ($isFirstUser) {
                $this->userRepository->setAdmin($user, true);
            }

            $this->characterRepository->create(
                $verifyOauthData,
                $user
            );

            Auth::login($user, true);

            return;
        }

        $user = $character->user;

        if ($character->CharacterID !== $user->main_character_id) {
            return;
        }

        $this->characterRepository->update(
            $verifyOauthData,
            $character,
            $user
        );

        Auth::login($user, true);
    }
}
