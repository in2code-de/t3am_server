<?php
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

use TYPO3\CMS\Core\Database\DatabaseConnection;
use TYPO3\CMS\Core\DataHandling\DataHandler;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Saltedpasswords\Salt\SaltFactory;

/**
 * Class SecurityService
 */
class SecurityService
{
    /**
     * @var DatabaseConnection
     */
    protected $database;

    /**
     * SecurityService constructor.
     *
     * @SuppressWarnings(PHPMD.Superglobals)
     */
    public function __construct()
    {
        $this->database = $GLOBALS['TYPO3_DB'];
    }

    /**
     * @param DataHandler $dataHandler
     */
    public function processDatamap_beforeStart(DataHandler $dataHandler)
    {
        if (!empty($dataHandler->datamap['tx_t3amserver_client'])) {
            foreach (array_keys($dataHandler->datamap['tx_t3amserver_client']) as $uid) {
                if (is_string($uid) && 0 === strpos($uid, 'NEW')) {
                    $dataHandler->datamap['tx_t3amserver_client'][$uid]['token'] = GeneralUtility::hmac(
                        GeneralUtility::generateRandomBytes(256),
                        'tx_t3amserver_client'
                    );
                }
            }
        }
    }

    /**
     * @param string $token
     * @return bool
     */
    public function isValid($token)
    {
        if (!is_string($token)) {
            return false;
        }
        $where = 'token = ' . $this->database->fullQuoteStr($token, 'tx_t3amserver_client');
        return (bool)$this->database->exec_SELECTcountRows('*', 'tx_t3amserver_client', $where);
    }

    /**
     * @return array
     */
    public function createEncryptionKey()
    {
        $config = array(
            'digest_alg' => 'sha512',
            'private_key_bits' => 1024,
            'private_key_type' => OPENSSL_KEYTYPE_RSA,
        );

        $res = openssl_pkey_new($config);
        openssl_pkey_export($res, $privateKey);
        $pubKey = openssl_pkey_get_details($res);

        $this->database->exec_INSERTquery('tx_t3amserver_keys', ['crdate' => time(), 'key_value' => $privateKey]);
        return ['pubKey' => $pubKey['key'], 'encryptionId' => $this->database->sql_insert_id()];
    }

    public function authUser($user, $password, $encryptionId)
    {
        $where = 'uid = ' . (int)$encryptionId;

        $keyRow = $this->database->exec_SELECTgetSingleRow('*', 'tx_t3amserver_keys', $where);
        if (!is_array($keyRow)) {
            return false;
        }
        $this->database->exec_DELETEquery('tx_t3amserver_keys', $where);

        if (!@openssl_private_decrypt(base64_decode($password), $plainPassword, $keyRow['key_value'])) {
            return false;
        }

        $userRow = GeneralUtility::makeInstance(UserRepository::class)->getUser($user);

        $saltingInstance = SaltFactory::getSaltingInstance($userRow['password']);
        return $saltingInstance->checkPassword($plainPassword, $userRow['password']);
    }
}
