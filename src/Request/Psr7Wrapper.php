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
namespace Horde\Controller\Request;
use \Horde_Controller_Request as H5Request;
use Psr\Http\Message\ServerRequestInterface;
use \Horde_Stream_String as StringStream;

/**
 * Wrap a PSR-7 Request inside a H5 request
 *
 * @author   Ralf Lang <lang@b1-systems.de>
 * @category  Horde
 * @copyright 2008-2021 Horde LLC
 * @license   http://www.horde.org/licenses/bsd BSD
 * @package   Controller
 */
class Psr7Wrapper implements H5Request
{
    private ServerRequestInterface $request;

    public function __construct(ServerRequestInterface $request)
    {
        $this->request = $request;
    }
    /**
     */
    public function getPath()
    {
        return $this->request->getUri()->getPath();
    }

    /**
     */
    public function getMethod()
    {
        return $this->request->getMethod();
    }

    /**
     */
    public function getGetVars()
    {
        $getVars = [];
        parse_str($this->request->getUri()->getQuery(), $getVars);
        return $getVars;
    }

    /**
     */
    public function getFileVars()
    {
        return $this->request->getUploadedFiles();
    }

    /**
     */
    public function getServerVars()
    {
        return $this->request->getServerParams();
    }

    /**
     */
    public function getPostVars()
    {
        return $this->request->getParsedBody();
    }

    /**
     */
    public function getCookieVars()
    {
        return $this->request->getCookieParams();
    }

    /**
     * Mimic $_REQUEST by merging get, post and cookies
     */
    public function getRequestVars()
    {
        return array_merge(
            $this->getGetVars(),
            $this->getPostVars(),
            $this->getCookieVars
        );
    }

    /**
     */
    public function getSessionId()
    {
        return 0;
    }

    /**
     * The request body if it is not form-encoded
     * @returns Horde_Stream
     */
    public function getRequestBody()
    {
        return new StringStream(
            ['string' => $this->request->getBody()->getContents()]
        );
    }
}
