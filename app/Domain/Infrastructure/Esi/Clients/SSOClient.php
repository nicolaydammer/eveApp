<?php

namespace App\Domain\Infrastructure\Esi\Clients;

use App\Domain\Auth\DTO\TokenData;
use App\Domain\Auth\DTO\VerifyOauthData;
use App\Domain\Auth\Entities\Character;
use App\Domain\Infrastructure\Configuration\Repositories\ConfigurationRepository;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\RateLimiter;

class SSOClient
{
    public function __construct(private ConfigurationRepository $configurationRepository) {}

    public function getAuthorizationUrl(): string
    {
        $scopes = $this->configurationRepository->get('esi_scopes');
        $scopes = implode(' ', $scopes['configuration']);

        return 'https://login.eveonline.com/v2/oauth/authorize?' . http_build_query([
            'response_type' => 'code',
            'client_id' => config('eve.client_id'),
            'redirect_uri' => config('eve.redirect_uri'),
            'scope' => $scopes,
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
            throw new Exception('Failed to exchange code: ' . $response->body());
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

        $scopes = json_decode(base64_decode(explode('.', $tokenData->accessToken)[1]), true)['scp'];

        return new VerifyOauthData(
            CharacterID: $response->json('CharacterID'),
            CharacterName: $response->json('CharacterName'),
            accessToken: $tokenData->accessToken,
            refreshToken: $tokenData->refreshToken,
            expiresAt: now()->addSeconds($tokenData->expiresIn),
            scopes: $scopes
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

    public function testToken(int $CharacterID)
    {
        $key = 'test-token:' . Auth::user()->id . ':' . $CharacterID;

        if (RateLimiter::tooManyAttempts($key, 5)) {
            return response(status: 429);
        }

        RateLimiter::hit($key, 300);

        $character = Character::query()->findOrFail($CharacterID);

        try {
            $accessToken = $this->getValidAccessToken($character);

            $response = Http::withToken($accessToken)
                ->acceptJson()
                ->get('https://login.eveonline.com/oauth/verify');

            if ($response->failed()) {
                return response(status: 401);
            }

            return response(status: 204);
        } catch (Exception $exception) {
            return response(status: 401);
        }
    }
}
