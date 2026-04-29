<?php

namespace App\Application\Auth;

use App\Domain\EVE\External\SSOClient;
use App\Domain\EVE\State\CharacterRepository;
use App\Domain\Shared\User\UserRepository;
use Illuminate\Support\Facades\Auth;

class HandleEveCallback
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

    public function handle(string $code)
    {
        $tokenData = $this->SSOClient->exchangeCode($code);

        $characterData = $this->SSOClient->verifyLogin($tokenData);

        $character = $this->characterRepository->find($characterData->CharacterId);

        $user = null;

        if (is_null($character)) {

            $user = $this->userRepository->create($characterData->CharacterId);

            $this->characterRepository->create($characterData, $user);
        }

        if ($character) {
            $user = $character->first()->user()->get()->first();

            $this->characterRepository->update($characterData);
        }

        Auth::login($user);

        return redirect()->route('dashboard');
    }
}
