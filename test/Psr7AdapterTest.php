<?php

namespace Horde\Controller\Test;

use Horde_Test_Case as HordeTestCase;
use Horde\Http\ResponseFactory;
use Horde\Http\StreamFactory;
use Horde_Controller_Response as H5Response;

use Horde\Controller\Response\Psr7Adapter;

class Psr7AdapterTest extends HordeTestCase
{
    protected function setUp(): void
    {
        $streamFactory = new StreamFactory();
        $responseFactory = new ResponseFactory();
        $this->adapter = new Psr7Adapter($responseFactory, $streamFactory);
    }

    public function testWithoutStatusCodeHeader()
    {
        $headers = [
            'foo' => 'bar',
            'Content-Type' => 'text/html; charset=UTF-8',
        ];
        $h5Response = new H5Response();
        $h5Response->setHeaders($headers);
        $response = $this->adapter->createPsr7Response($h5Response);
        $responseHeaders = $response->getHeaders();
        $expectedHeaders = [];
        foreach ($headers as $name => $value) {
            $expectedHeaders[$name] = [$value];
        }
        $this->assertEquals($expectedHeaders, $responseHeaders);
    }

    public function testWithStatusCodeAndCustomReason()
    {
        $headers = [
            'foo' => 'bar',
            'HTTP/1.1 404' => 'Coud Not Find',
        ];
        $h5Response = new H5Response();
        $h5Response->setHeaders($headers);
        $response = $this->adapter->createPsr7Response($h5Response);
        $responseHeaders = $response->getHeaders();
        $expectedHeaders = [
            'foo' => ['bar'],
        ];

        $this->assertEquals($expectedHeaders, $responseHeaders);
        $this->assertEquals(404, $response->getStatusCode());
        $this->assertEquals('Coud Not Find', $response->getReasonPhrase());
    }

    public function testWithStatusCodeHeaderDefaultReason()
    {
        $headers = [
            'foo' => 'bar',
            'HTTP/1.1 404' => null,
        ];
        $h5Response = new H5Response();
        $h5Response->setHeaders($headers);
        $response = $this->adapter->createPsr7Response($h5Response);
        $responseHeaders = $response->getHeaders();
        $expectedHeaders = [
            'foo' => ['bar'],
        ];

        $this->assertEquals($expectedHeaders, $responseHeaders);
        $this->assertEquals(404, $response->getStatusCode());
        $this->assertEquals('Not Found', $response->getReasonPhrase());
    }
}
