<?php

namespace App\Http\Controllers\Web\Admin;

use App\Domain\Auth\Entities\Character;
use App\Domain\Infrastructure\Esi\Clients\SSOClient;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CharacterController
{
    public function index()
    {
        return Inertia::render('Admin/Characters');
    }

    public function listCharacters(Request $request)
    {
        $search = $request->string('search')->trim()->toString();

        return Character::query()
            ->with([
                'user:id,main_character_id',
                'user.mainCharacter:CharacterID,CharacterName'
            ])
            ->when($search !== '', function ($query) use ($search) {
                $query->where(
                    'CharacterName',
                    'like',
                    '%' . $search . '%'
                );
            })
            ->select([
                'CharacterID',
                'CharacterName',
                'user_id',
                'scopes',
            ])
            ->orderBy('CharacterName')
            ->paginate(25);
    }

    public function testToken(Request $request, int $CharacterID, SSOClient $SSOClient)
    {
        return $SSOClient->TestToken($CharacterID);
    }
}
