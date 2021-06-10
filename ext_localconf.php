<?php
$GLOBALS['TYPO3_CONF_VARS']['FE']['eID_include']['t3am_server'] = \In2code\T3AM\Server\Server::class . '::processRequest';
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass']['tx_t3amserver'] = \In2code\T3AM\Server\SecurityService::class;
