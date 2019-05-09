<?php
declare(strict_types=1);
namespace In2code\T3AM\Server;

/*
 * Copyright (C) 2018 Oliver Eglseder <php@vxvr.de>, in2code GmbH
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 */

use Exception;
use ReflectionException;
use ReflectionMethod;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use function call_user_func_array;
use function header;
use function is_string;
use function json_encode;
use function settype;

/**
 * Class Server
 */
class Server
{
    /**
     * @var SecurityService
     */
    protected $tokenService = null;

    /**
     * @var array
     */
    protected $routes = [
        'check/ping' => [Server::class, 'ping'],
        'user/state' => [UserRepository::class, 'getUserState'],
        'user/auth' => [SecurityService::class, 'authUser'],
        'user/get' => [UserRepository::class, 'getUser'],
        'user/image' => [UserRepository::class, 'getUserImage'],
        'encryption/getKey' => [SecurityService::class, 'createEncryptionKey'],
    ];

    /**
     * Server constructor.
     */
    public function __construct()
    {
        $this->tokenService = GeneralUtility::makeInstance(SecurityService::class);
    }

    public function handle()
    {
        try {
            $data = $this->dispatch(GeneralUtility::_GET('token'), GeneralUtility::_GET('route'));
            $response = ['code' => 1496395280, 'error' => false, 'message' => 'ok', 'data' => $data];
        } catch (ServerException $e) {
            $response = ['code' => $e->getCode(), 'error' => true, 'message' => $e->getMessage(), 'data' => []];
        }

        header('Content-Type: application/json');
        echo json_encode($response);
    }

    /**
     * @param string $token
     * @param string $route
     *
     * @return mixed
     *
     * @throws ServerException
     */
    protected function dispatch(string $token, string $route)
    {
        if (!$this->tokenService->isValid($token)) {
            throw new ServerException('Access error', 1519999361);
        }

        if (!is_string($route) || !isset($this->routes[$route])) {
            throw new ServerException('Routing error', 1496395045);
        }

        list($class, $action) = $this->routes[$route];

        try {
            $arguments = $this->mapParametersToArguments($class, $action);
            $result = call_user_func_array([GeneralUtility::makeInstance($class), $action], $arguments);
        } catch (Exception $exception) {
            throw new ServerException('Exception: ' . $exception->getMessage(), 1496395387, $exception);
        }

        return $result;
    }

    /**
     * @param string $class
     * @param string $action
     *
     * @return array
     *
     * @throws ServerException
     */
    protected function mapParametersToArguments(string $class, string $action): array
    {
        $arguments = [];

        try {
            $reflectionMethod = new ReflectionMethod($class, $action);
        } catch (ReflectionException $e) {
            throw new ServerException('Can not examine route target', 1520607184, $e);
        }
        foreach ($reflectionMethod->getParameters() as $position => $reflectionParameter) {
            $parameter = $reflectionParameter->getName();
            $value = GeneralUtility::_GET($parameter);

            if (null === $value && !$reflectionParameter->allowsNull()) {
                throw new ServerException('Missing parameter $' . $parameter, 1496395204);
            } else {
                if (null !== ($type = $reflectionParameter->getType())) {
                    settype($value, $type);
                }
                $arguments[$position] = $value;
            }
        }
        return $arguments;
    }

    /**
     * @return bool
     */
    public function ping(): bool
    {
        return true;
    }
}

GeneralUtility::makeInstance(Server::class)->handle();
