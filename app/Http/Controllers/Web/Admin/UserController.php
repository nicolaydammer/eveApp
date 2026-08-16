<?php

namespace App\Http\Controllers\Web\Admin;

use App\Domain\Auth\Entities\User;
use App\Domain\Shared\User\UserRepository;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserController
{
    public function index()
    {
        return Inertia::render('Admin/Users');
    }

    public function getUsers(Request $request)
    {
        $perPage = 15;

        $search = $request->input('search');

        return User::query()
            ->with([
                'mainCharacter',
                'characters',
            ])
            ->when(
                $search,
                fn($query) => $query->whereHas(
                    'characters',
                    fn($query) => $query->where(
                        'CharacterName',
                        'ILIKE',
                        "%{$search}%"
                    )
                )
            )
            ->orderBy('id')
            ->paginate($perPage);
    }

    public function setAdmin(Request $request, User $user, UserRepository $userRepository)
    {
        $isAdmin = $request->boolean('is_admin');

        if (!$isAdmin && $user->is($request->user())) {
            return response()->json([
                'message' => 'You cannot remove your own administrator role.',
            ], 422);
        }

        $userRepository->setAdmin($user, $isAdmin);
    }
}
