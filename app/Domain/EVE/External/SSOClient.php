<?php

namespace App\Domain\EVE\External;

use App\Domain\EVE\DTO\CharacterData;
use App\Domain\EVE\DTO\TokenData;
use Illuminate\Support\Facades\Http;

class SSOClient
{
    public function getAuthorizationUrl(): string
    {
        return 'https://login.eveonline.com/v2/oauth/authorize?'.http_build_query([
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
            throw new \Exception('Failed to exchange code: '.$response->body());
        }

        return new TokenData(
            $response->json('access_token'),
            $response->json('refresh_token'),
            $response->json('expires_in')
        );
    }

    public function getCharacter(string $accessToken): CharacterData
    {
        $response = Http::withToken($accessToken)
            ->get('https://login.eveonline.com/oauth/verify');

        return new CharacterData(
            $response->json('CharacterID'),
            $response->json('CharacterName')
        );
    }
}
