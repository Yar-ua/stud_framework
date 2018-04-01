<?php

namespace Mindk\Framework\Http\Response;

/**
 * Class RedirectResponse
 *
 * @package Mindk\Framework\Http\Response
 */
class RedirectResponse extends Response
{
    //@TODO: Implement this
    public function __construct($url, $code = 301) {
    	$this->code = $code;
    	$this->setHeader('Location', $url);
    }
}