<?php
namespace In2code\T3AM\Server;

use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Database\DatabaseConnection;

class UserRepository
{
    /**
     * @var DatabaseConnection
     */
    protected $connection = null;

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
        return $this->connection->exec_SELECTgetSingleRow('*', 'be_users', $where);
    }
}
