<?php
/**
 * Copyright 2008-2021 Horde LLC (http://www.horde.org/)
 *
 * See the enclosed file LICENSE for license information (BSD). If you
 * did not receive this file, see http://www.horde.org/licenses/bsd.
 *
 * @author   Ralf Lang <lang@b1-systems.de>
 * @category Horde
 * @license  http://www.horde.org/licenses/bsd BSD
 * @package  Controller
 */
namespace Horde\Controller\Response;
use \Horde_Controller_Response as H5Response;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Create a PSR-7 Response from a H5 Controller Response
 *
 * @author   Ralf Lang <lang@b1-systems.de>
 * @category  Horde
 * @copyright 2008-2021 Horde LLC
 * @license   http://www.horde.org/licenses/bsd BSD
 * @package   Controller
 */
class Psr7Adapter implements H5Response
{
    private ResponseFactoryInterface $responseFactory;
    public function __construct(ResponseFactoryInterface $responseFactory)
    {
        $this->responseFactory = $responseFactory;        
    }

    public function createPsr7Response(H5Response $response): ResponseInterface
    {
        $psrResponse = $this->responseFactory->createResponse();
        $psrResponse = $psrResponse->withBody($response->getBody());
        foreach ($response->getHeaders() as $name => $value) {
            $psrResponse = $psrResponse->withHeader($name, $value);            
        }
        return $psrResponse;
    }
}
