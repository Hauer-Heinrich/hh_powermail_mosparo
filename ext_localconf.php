<?php
defined('TYPO3') || die();

call_user_func(function(string $extensionKey) {

    // Custom log file
    $GLOBALS['TYPO3_CONF_VARS']['LOG']['HauerHeinrich']['HhPowermailMosparo']['Domain']['Validator']['SpamShield']['MosparoMethod']['writerConfiguration'] = [
        // configuration for ERROR level log entries
        \TYPO3\CMS\Core\Log\LogLevel::ERROR => [
            // Add a FileWriter
            \TYPO3\CMS\Core\Log\Writer\FileWriter::class => [
                // Configuration for the writer
                'logFile' => \TYPO3\CMS\Core\Core\Environment::getVarPath() . '/log/ext_hhpowermailmosparo.log',
            ],
        ],
        \TYPO3\CMS\Core\Log\LogLevel::WARNING => [
            \TYPO3\CMS\Core\Log\Writer\FileWriter::class => [
                'logFile' => \TYPO3\CMS\Core\Core\Environment::getVarPath() . '/log/ext_hhpowermailmosparo.log',
            ],
        ],
        \TYPO3\CMS\Core\Log\LogLevel::DEBUG => [
            \TYPO3\CMS\Core\Log\Writer\FileWriter::class => [
                'logFile' => \TYPO3\CMS\Core\Core\Environment::getVarPath() . '/log/ext_hhpowermailmosparo_debug.log',
            ],
        ],
    ];

}, 'hh_powermail_mosparo');
