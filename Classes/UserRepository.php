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

use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Database\DatabaseConnection;
use TYPO3\CMS\Core\Resource\Exception\FileDoesNotExistException;
use TYPO3\CMS\Core\Resource\File;
use TYPO3\CMS\Core\Resource\ResourceFactory;

/**
 * Class UserRepository
 */
class UserRepository
{
    /**
     * @var DatabaseConnection
     */
    protected $connection = null;

    /**
     * @var array
     */
    protected $fields = [
        'tstamp',
        'username',
        'description',
        'avatar',
        'password',
        'admin',
        'disable',
        'starttime',
        'endtime',
        'lang',
        'email',
        'crdate',
        'realName',
        'disableIPlock',
        'deleted',
    ];

    /**
     * BackendUserRepository constructor.
     *
     * @SuppressWarnings(PHPMD.Superglobals)
     */
    public function __construct()
    {
        $this->connection = $GLOBALS['TYPO3_DB'];
    }

    /**
     * @param string $user
     *
     * @return string
     */
    public function getUserState($user)
    {
        $where = 'username = ' . $this->connection->fullQuoteStr($user, 'be_users');
        $whereActive = $where . BackendUtility::deleteClause('be_users') . BackendUtility::BEenableFields('be_users');

        if ($this->connection->exec_SELECTcountRows('*', 'be_users', $whereActive)) {
            return 'okay';
        }

        if ($this->connection->exec_SELECTcountRows('*', 'be_users', $where)) {
            return 'deleted';
        }
        return 'unknown';
    }

    /**
     * @param string $user
     *
     * @return array
     */
    public function getUser($user)
    {
        $where = 'username = ' . $this->connection->fullQuoteStr($user, 'be_users');
        return $this->connection->exec_SELECTgetSingleRow(implode(',', $this->fields), 'be_users', $where);
    }

    /**
     * @param string $user
     *
     * @return null|array
     */
    public function getUserImage($user)
    {
        $sql = '
        SELECT sys_file.* FROM sys_file
        RIGHT JOIN sys_file_reference ON sys_file.uid = sys_file_reference.uid_local
        RIGHT JOIN be_users ON sys_file_reference.uid_foreign = be_users.uid 
            WHERE be_users.username = ' . $this->connection->fullQuoteStr($user, 'be_users') . '
            AND sys_file_reference.deleted = 0
            AND sys_file_reference.tablenames = "be_users"
            AND sys_file_reference.fieldname = "avatar"';

        $file = $this->connection->admin_query($sql)->fetch_assoc();
        if (!empty($file['uid'])) {
            try {
                $resource = ResourceFactory::getInstance()->getFileObject($file['uid'], $file);
                if ($resource instanceof File && $resource->exists()) {
                    return [
                        'identifier' => $resource->getName(),
                        'b64content' => base64_encode($resource->getContents()),
                    ];
                }
            } catch (FileDoesNotExistException $e) {
            }
        }
        return null;
    }
}
