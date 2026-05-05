<?php

namespace App\Http\Controllers\Web;


use App\Domain\Dashboard\Factories\EsiGatewayFactory;
use App\Domain\Dashboard\ViewModels\DashboardViewModel;
use App\Domain\Shared\User\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class DashboardController
{
    public function __construct(private EsiGatewayFactory $esiGatewayFactory) {}

    public function index(Request $request): \Inertia\Response
    {
        $query = Auth::user()->characters()
            ->orderByRaw('"characters"."CharacterID" = ? DESC', [Auth::user()->main_character_id])
            ->orderBy('CharacterName', 'asc');

        if ($request->filled('search')) {
            $query->where('CharacterName', 'like', '%' . $request->search . '%');
        }

        $paginatedCharacters = $query->paginate(16)
            ->withQueryString()
            ->through(function ($character) {

                $charDto = $this->esiGatewayFactory->character()->get($character->CharacterID);
                $corpDto = $this->esiGatewayFactory->corporation()->get($charDto->corporation_id);
                $allianceDto = $charDto->alliance_id ? $this->esiGatewayFactory->alliance()->get($charDto->alliance_id) : null;

                return (new DashboardViewModel(
                    Auth::user()->main_character_id,
                    $charDto,
                    $corpDto,
                    $allianceDto,
                ))->toArray();
            });

        return Inertia::render('Dashboard', [
            'characters' => $paginatedCharacters,
            'filters' => $request->only(['search']),
        ]);
    }

    public function setMainCharacter(int $CharacterID, UserRepository $userRepository): void
    {
        $userRepository->setMainCharacter(Auth::user(), $CharacterID);
    }
}
