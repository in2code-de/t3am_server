<?php

declare(strict_types=1);

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use In2code\T3AM\Server\Server;
use In2code\T3AM\Server\SecurityService;

defined('TYPO3') or die();

(function () {
    $GLOBALS['TYPO3_CONF_VARS']['FE']['eID_include']['t3am_server'] = Server::class . '::processRequest';
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass']['tx_t3amserver'] = SecurityService::class;
})();
