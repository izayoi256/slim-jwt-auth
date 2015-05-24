<?php

/*
 * This file is part of Slim JSON Web Token Authentication middleware
 *
 * Copyright (c) 2015 Mika Tuupola
 *
 * Licensed under the MIT license:
 *   http://www.opensource.org/licenses/mit-license.php
 *
 * Project home:
 *   https://github.com/tuupola/slim-jwt-auth
 *
 */

namespace Slim\Middleware\JwtAuthentication;

use Psr\Http\Message\RequestInterface;

class RequestPathRule implements RuleInterface
{
    protected $options = [
        "path" => "/",
        "passthrough" => []
    ];

    public function __construct($options = [])
    {
        $this->options = array_merge($this->options, $options);
    }

    public function __invoke(RequestInterface $request)
    {
        /* If request path is matches passthrough should not authenticate. */
        foreach ($this->options["passthrough"] as $passthrough) {
            $passthrough = rtrim($passthrough, "/");
            if (!!preg_match("@^{$passthrough}(/.*)?$@", $request->getUri()->getPath())) {
                return false;
            }
        }

        /* Otherwise check if path matches and we should authenticate. */
        $path = rtrim($this->options["path"], "/");
        return !!preg_match("@^{$path}(/.*)?$@", $request->getUri()->getPath());
    }
}
