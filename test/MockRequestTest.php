<?php

/**
 * Test the mock request handler.
 *
 * PHP version 5
 *
 * @category   Horde
 * @package    Controller
 * @subpackage UnitTests
 * @author     Gunnar Wrobel <wrobel@pardus.de>
 * @license    http://www.horde.org/licenses/bsd
 */

namespace Horde\Controller;

use Horde_Test_Case as TestCase;
use Horde_Controller_Request_Mock;

/**
 * Test the mock request handler.
 *
 * Copyright 2011-2026 Horde LLC (http://www.horde.org/)
 *
 * @category   Horde
 * @package    Controller
 * @subpackage UnitTests
 * @author     Gunnar Wrobel <wrobel@pardus.de>
 * @license    http://www.horde.org/licenses/bsd
 * @coversNothing
 */
class MockRequestTest extends TestCase
{
    public function testEmptyGetPath()
    {
        $r = new Horde_Controller_Request_Mock();
        $this->assertNull($r->getPath());
    }

    public function testSetPath()
    {
        $r = new Horde_Controller_Request_Mock();
        $r->setPath('R');
        $this->assertEquals('R', $r->getPath());
    }

    public function testGetPathRedirectUrl()
    {
        $r = new Horde_Controller_Request_Mock(
            ['SERVER' => ['REDIRECT_URL' => 'RE']]
        );
        $this->assertEquals('RE', $r->getPath());
    }

    public function testGetPathRequestUri()
    {
        $r = new Horde_Controller_Request_Mock(
            ['SERVER' => ['REQUEST_URI' => 'RU']]
        );
        $this->assertEquals('RU', $r->getPath());
    }

    /**
     * @dataProvider provideGets
     */
    public function testGetGetVars($method, $key, $value)
    {
        $r = new Horde_Controller_Request_Mock([$key => $value]);
        $this->assertEquals($value, $r->{$method}());
    }

    public function provideGets()
    {
        return [
            ['getGetVars', 'GET', ['X' => 'Y']],
            ['getFileVars', 'files', ['X' => 'Y']],
            ['getServerVars', 'server', ['X' => 'Y']],
            ['getPostVars', 'POST', ['X' => 'Y']],
            ['getCookieVars', 'cookie', ['X' => 'Y']],
            ['getRequestVars', 'REQUEST', ['X' => 'Y']],
        ];
    }

    public function testGetHeaders()
    {
        $r = new Horde_Controller_Request_Mock(
            ['SERVER' => ['HTTP_TEST' => 'test']]
        );
        $this->assertEquals(['test' => 'test'], $r->getHeaders());
    }

    public function testGetHeaderNames()
    {
        $r = new Horde_Controller_Request_Mock(
            ['SERVER' => ['HTTP_TEST' => 'test']]
        );
        $this->assertEquals(['test'], $r->getHeaderNames());
    }
}
