<?php
defined('TYPO3') || die();

call_user_func(function(string $extensionKey) {
    // make TypoScript selectable
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
        $extensionKey,
        'Configuration/TypoScript',
        'EXT:'.$extensionKey.' :: Powermail Mosparo SPAM protection'
    );
}, 'hh_powermail_mosparo');
