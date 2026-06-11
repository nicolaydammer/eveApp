<?php

namespace App\Domain\Infrastructure\Esi\Clients;

use App\Domain\Auth\DTO\TokenData;
use App\Domain\Auth\DTO\VerifyOauthData;
use App\Domain\Auth\Entities\Character;
use Illuminate\Support\Facades\Http;

class SSOClient
{
    public function getAuthorizationUrl(): string
    {
        return 'https://login.eveonline.com/v2/oauth/authorize?' . http_build_query([
            'response_type' => 'code',
            'client_id' => config('eve.client_id'),
            'redirect_uri' => config('eve.redirect_uri'),
            'scope' => config('eve.scopes'),
            'state' => csrf_token(),
        ]);
    }

    public function exchangeCode(string $code): TokenData
    {
        $response = Http::asForm()
            ->withBasicAuth(config('eve.client_id'), config('eve.client_secret'))
            ->post('https://login.eveonline.com/v2/oauth/token', [
                'grant_type' => 'authorization_code',
                'code' => $code,
            ]);

        if ($response->failed()) {
            throw new \Exception('Failed to exchange code: ' . $response->body());
        }

        return new TokenData(
            $response->json('access_token'),
            $response->json('refresh_token'),
            $response->json('expires_in')
        );
    }

    public function verifyLogin(TokenData $tokenData): VerifyOauthData
    {
        $response = Http::withToken($tokenData->accessToken)
            ->acceptJson()
            ->get('https://login.eveonline.com/oauth/verify');

        if ($response->failed()) {
            throw new \Exception('Failed to verify token: ' . $response->body());
        }

        return new VerifyOauthData(
            CharacterID: $response->json('CharacterID'),
            CharacterName: $response->json('CharacterName'),
            accessToken: $tokenData->accessToken,
            refreshToken: $tokenData->refreshToken,
            expiresAt: now()->addSeconds($tokenData->expiresIn)
        );
    }

    public function getValidAccessToken(Character $character): string
    {
        if ($character->expires_at->subSeconds(60)->isPast()) {
            $response = Http::asForm()
                ->withBasicAuth(config('eve.client_id'), config('eve.client_secret'))
                ->post('https://login.eveonline.com/v2/oauth/token', [
                    'grant_type' => 'refresh_token',
                    'refresh_token' => $character->refreshToken,
                ]);

            if ($response->failed()) {
                throw new \Exception('Failed to refresh token: ' . $response->body());
            }

            $character->update([
                'accessToken' => $response->json('access_token'),
                'refreshToken' => $response->json('refresh_token'),
                'expires_at' => now()->addSeconds($response->json('expires_in')),
            ]);
        }

        return $character->accessToken;
    }
}
