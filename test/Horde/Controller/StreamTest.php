<?php

namespace Horde\Controller;
use Horde_Test_Case as TestCase;
use \Horde_Support_StringStream;
use \Horde_Controller_Response;
use \Horde_Controller_ResponseWriter_Web;

class StreamTest extends TestCase
{
    public function testStreamOutput()
    {
        $output = 'BODY';
        $body = new Horde_Support_StringStream($output);
        $response = new Horde_Controller_Response();
        $response->setBody($body->fopen());
        $writer = new Horde_Controller_ResponseWriter_Web();
        ob_start();
        $writer->writeResponse($response);
        $this->assertEquals('BODY', ob_get_clean());
    }
}
