<?php

/*
 * This file is part of the UptimeMonitor package.
 *
 * (c) Matthieu Mota <matthieu@boxydev.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MatthieuMota\Component\UptimeMonitor\Tests;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use MatthieuMota\Component\UptimeMonitor\UptimeMonitor;
use PHPUnit\Framework\TestCase;

class UptimeMonitorTest extends TestCase
{
    public function testUptimeReturnStatus()
    {
        $handler = new MockHandler([
            new Response(200),
            new Response(500),
            new RequestException('Bad SSL certificate', new Request('GET', 'c'))
        ]);

        $uptimeMonitor = new UptimeMonitor(
            new Client(['handler' => $handler])
        );

        $uptimeMonitor
            ->add('a')
            ->add('b')
            ->add('c')
        ;

        $this->expectOutputString("a is up ! ✅\nb is down ! ❌ (500)\nc is down ! ❌ (Bad SSL certificate)\n");
        $uptimeMonitor->check();
    }
}
