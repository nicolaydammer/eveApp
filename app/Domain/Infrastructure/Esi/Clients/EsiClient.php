<?php

namespace App\Domain\Infrastructure\Esi\Clients;

use App\Domain\Auth\Entities\Character;
use App\Domain\Health\Exceptions\EsiRateLimitException;
use App\Domain\Health\Exceptions\EsiRequestFailedException;
use App\Domain\Health\Exceptions\MissingEsiScopeException;
use App\Domain\Infrastructure\Esi\Enums\PaginationType;
use App\Domain\Infrastructure\Esi\Requests\EsiRequest;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\RateLimiter;

class EsiClient
{
    private SSOClient $SSOClient;
    private string $baseUrl = 'https://esi.evetech.net';
    private PendingRequest $http;

    public function __construct(SSOClient $SSOClient)
    {
        $this->SSOClient = $SSOClient;

        $this->http = Http::acceptJson()
            ->withOptions([
                'curl' => [
                    CURLOPT_TCP_KEEPALIVE => 1,
                    CURLOPT_TCP_KEEPIDLE  => 120,
                ],
            ]);
    }

    public function get(EsiRequest $request): array
    {
        if (is_null($request->id())) {
            $endpoint = $request->endpoint();
        } else {
            $endpoint = sprintf($request->endpoint(), $request->id());
        }

        $character = $request->character();
        $scopes = $request->requiredScopes();

        $this->checkScopes($character, $scopes);

        $data = $request->data();

        $response = $this->request('GET', $endpoint, $character, $data);

        if ($response->failed()) {
            throw new EsiRequestFailedException(
                endpoint: $endpoint,
                method: 'GET',
                status: $response->status(),
                character: $character ?? null,
                message: $response->json('error') ?? $response->body()
            );
        }

        switch ($request->paginated()) {
            case PaginationType::Page:
                $page = 1;
                $pages = $response->header('X-Pages');
                $responseData = [$page => $response->json()];

                while ($page < $pages) {
                    $page++;
                    $data['page'] = $page;
                    $response = $this->request('GET', $endpoint, $character, $data);

                    if ($response->failed()) {
                        throw new EsiRequestFailedException(
                            endpoint: $endpoint,
                            method: 'GET',
                            status: $response->status(),
                            character: $character ?? null,
                            message: $response->json('error') ?? $response->body()
                        );
                    }

                    $responseData[$page] = $response->json();
                }

                return $responseData;
            case PaginationType::Cursor:
            case PaginationType::None:
            default:
                return $response->json() ?? [];
        }
    }

    public function post(EsiRequest $request): array
    {
        if (is_null($request->id())) {
            $endpoint = $request->endpoint();
        } else {
            $endpoint = sprintf($request->endpoint(), $request->id());
        }

        $character = $request->character();
        $scopes = $request->requiredScopes();

        $this->checkScopes($character, $scopes);

        $response = $this->request('POST', $endpoint, $character, $request->data());

        if ($response->failed()) {
            throw new EsiRequestFailedException(
                endpoint: $endpoint,
                method: 'POST',
                status: $response->status(),
                character: $character ?? null,
                message: $response->json('error') ?? $response->body()
            );
        }

        return $response->json() ?? [];
    }

    private function request(string $method, string $endpoint, ?Character $character = null, array $data = []): Response
    {
        if (Cache::get('esi:cooldown')) {
            throw new EsiRequestFailedException(
                endpoint: $endpoint,
                method: $method,
                character: $character ?? null,
                message: 'ESI requests are temporarily blocked due to reaching the retry limit.'
            );
        }

        // Wrap everything in retry logic so that Lock or RateLimit failures trigger a retry
        return $this->retry(function () use ($method, $endpoint, $character, $data) {
            return $this->withLock($endpoint, function () use ($method, $endpoint, $character, $data) {
                return $this->rateLimit($method, $endpoint, $character, function () use ($method, $endpoint, $character, $data) {
                    return $this->performRequest($method, $endpoint, $character, $data);
                });
            });
        });
    }

    private function performRequest(string $method, string $endpoint, ?Character $character, array $data): Response
    {
        $url = "{$this->baseUrl}{$endpoint}";
        $cacheKey = $this->cacheKey($url, $character, $data);

        $cached = Cache::get($cacheKey);

        $request = clone($this->http);

        $request = $request->withHeaders([
            'X-Compatibility-Date' => now()->subDay()->toDateString()
        ]);

        if ($character) {
            $token = $this->SSOClient->getValidAccessToken($character);
            $request = $request->withToken($token);
        }

        if ($cached && isset($cached['etag'])) {
            $request = $request->withHeaders(['If-None-Match' => $cached['etag']]);
        }

        /** @var Response $response */
        $response = $request->$method($url, $data);

        // Handle 304: Return a manual response object containing cached data
        if ($response->status() === 304 && $cached) {
            return new Response(new \GuzzleHttp\Psr7\Response(200, $cached['headers'], json_encode($cached['data'])));
        }

        $this->handleEsiHeaders($response);

        if ($response->successful()) {
            $etag = $response->header('ETag');

            if ($etag) {
                Cache::put($cacheKey, [
                    'etag' => $etag,
                    'data' => $response->json(),
                    'headers' => $response->headers()
                ], now()->addHours(6));
            }
        }

        return $response;
    }

    private function rateLimit(string $method, string $endpoint, ?Character $character, callable $callback)
    {
        $executed = RateLimiter::attempt('esi:global', 1000, $callback);

        if ($executed === false) {
            throw new EsiRateLimitException(
                endpoint: $endpoint,
                method: $method,
                character: $character,
            );
        }

        return $executed;
    }

    private function retry(callable $callback)
    {
        $attempts = 0;
        $maxAttempts = 3;

        while (true) {
            try {
                /** @var Response $response */
                $response = $callback();

                if ($response->status() === 420) {
                    throw new \Exception("ESI Error: {$response->status()}");
                }

                return $response;
            } catch (\Exception $e) {
                $attempts++;
                if ($attempts >= $maxAttempts) throw $e;

                sleep(pow(2, $attempts));
            }
        }
    }

    private function withLock(string $endpoint, callable $callback)
    {
        $lock = Cache::lock("esi:lock:" . md5($endpoint), 10);
        $result = $lock->get($callback);

        if ($result === false) {
            throw new \Exception('Could not acquire request lock');
        }

        return $result;
    }

    private function handleEsiHeaders(Response $response): void
    {
        $remain = $response->header('X-Esi-Error-Limit-Remain');
        $reset  = $response->header('X-Esi-Error-Limit-Reset');

        // If we are getting close to the limit (less than 10 errors left), trigger cooldown
        if ($remain !== null && (int)$remain < 10) {
            Cache::put('esi:cooldown', true, (int)$reset);
        }
    }

    private function checkScopes(?Character $character, array $requiredScopes): void
    {
        if (empty($character) || is_null($character) || empty($requiredScopes)) return;

        foreach ($requiredScopes as $scope) {
            if (!$character->hasScope($scope)) {
                throw new MissingEsiScopeException(
                    scope: $scope,
                    character: $character,
                    healthCode: 'esi.request.missing-scope',
                );
            }
        }
    }

    private function cacheKey(
        string $url,
        ?Character $character,
        array $data,
    ): string {
        return 'esi:etag:' . md5(
            $url .
                ($character?->id ?? 'public') .
                json_encode($data),
        );
    }
}
