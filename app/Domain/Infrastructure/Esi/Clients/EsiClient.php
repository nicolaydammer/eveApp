<?php

namespace App\Domain\Infrastructure\Esi\Clients;

use App\Domain\Auth\Entities\Character;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\RateLimiter;

class EsiClient
{
    private SSOClient $SSOClient;
    private string $baseUrl = 'https://esi.evetech.net';

    public function __construct(SSOClient $SSOClient)
    {
        $this->SSOClient = $SSOClient;
    }

    public function get(string $endpoint, ?Character $character = null): array
    {
        $response = $this->request('GET', $endpoint, $character);

        return $response->json() ?? [];
    }

    public function post(string $endpoint, array $data = [], ?Character $character = null): array
    {
        $response = $this->request('POST', $endpoint, $character, $data);
        return $response->json() ?? [];
    }

    private function request(string $method, string $endpoint, ?Character $character = null, array $data = []): Response
    {
        if (Cache::get('esi:cooldown')) {
            throw new \Exception('ESI is cooling down due to error limits.');
        }

        // Wrap everything in retry logic so that Lock or RateLimit failures trigger a retry
        return $this->retry(function () use ($method, $endpoint, $character, $data) {
            return $this->withLock($endpoint, function () use ($method, $endpoint, $character, $data) {
                return $this->rateLimit(function () use ($method, $endpoint, $character, $data) {
                    return $this->performRequest($method, $endpoint, $character, $data);
                });
            });
        });
    }

    private function performRequest(string $method, string $endpoint, ?Character $character, array $data): Response
    {
        $url = "{$this->baseUrl}{$endpoint}";
        $cacheKey = "esi:etag:" . md5($url . ($character->id ?? 'public'));

        $cached = Cache::get($cacheKey);
        $request = Http::acceptJson();

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
            return new Response(new \GuzzleHttp\Psr7\Response(200, [], json_encode($cached['data'])));
        }

        $this->handleEsiHeaders($response);

        if ($response->successful()) {
            $etag = $response->header('ETag');
            if ($etag) {
                Cache::put($cacheKey, [
                    'etag' => $etag,
                    'data' => $response->json(),
                ], now()->addHours(6));
            }
        }

        return $response;
    }

    private function rateLimit(callable $callback)
    {
        $executed = RateLimiter::attempt('esi:global', 100, $callback);

        if ($executed === false) {
            throw new \Exception('Local rate limit reached');
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

                if ($response->status() === 420 || $response->status() >= 500) {
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
}
