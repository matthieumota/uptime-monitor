<?php

/*
 * This file is part of the UptimeMonitor package.
 *
 * (c) Matthieu Mota <matthieu@boxydev.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MatthieuMota\Component\UptimeMonitor;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Pool;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\ResponseInterface;

class UptimeMonitor
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @var array
     */
    private $urls;

    public function __construct(Client $client = null)
    {
        $this->client = $client ?? new Client();
    }

    public function add(string $url): self
    {
        $this->urls[] = $url;

        return $this;
    }

    public function check()
    {
        $requests = function () {
            foreach ($this->urls as $url) {
                yield new Request('GET', $url);
            }
        };

        $pool = new Pool($this->client, $requests(), [
            'concurrency' => 10,
            'fulfilled' => function (ResponseInterface $response, int $index) {
                if (200 === $response->getStatusCode()) {
                    echo $this->urls[$index].' is up ! ✅'.PHP_EOL;
                } else {
                    echo $this->urls[$index].' is down ! ❌ ('.$response->getStatusCode().')'.PHP_EOL;
                }
            },
            'rejected' => function (RequestException $exception, int $index) {
                echo $this->urls[$index].' is down ! ❌ ('.$exception->getMessage().')'.PHP_EOL;
            },
        ]);

        $pool->promise()->wait();
    }
}
