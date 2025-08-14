<?php
defined('TYPO3') || die();

call_user_func(function(string $extensionKey) {
    /**
     * extend powermail fields tx_powermail_domain_model_field
     */
    $tempColumns = [
        'mosparo_host' => [
            'exclude' => true,
            'label' => 'LLL:EXT:'.$extensionKey.'/Resources/Private/Language/locallang.xlf:mosparo_host.label',
            'description' => 'LLL:EXT:'.$extensionKey.'/Resources/Private/Language/locallang.xlf:mosparo_host.description',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'max' => 255,
                'eval' => 'trim',
            ]
        ],
        'mosparo_uuid' => [
            'exclude' => true,
            'label' => 'LLL:EXT:'.$extensionKey.'/Resources/Private/Language/locallang.xlf:mosparo_uuid.label',
            'description' => 'LLL:EXT:'.$extensionKey.'/Resources/Private/Language/locallang.xlf:mosparo_uuid.description',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'max' => 255,
                'eval' => 'trim',
            ]
        ],
        'mosparo_publickey' => [
            'exclude' => true,
            'label' => 'LLL:EXT:'.$extensionKey.'/Resources/Private/Language/locallang.xlf:mosparo_publickey.label',
            'description' => 'LLL:EXT:'.$extensionKey.'/Resources/Private/Language/locallang.xlf:mosparo_publickey.description',
            'config' => [
                'description' => '',
                'type' => 'input',
                'size' => 30,
                'max' => 255,
                'eval' => 'trim',
            ]
        ],
        'mosparo_privatekey' => [
            'exclude' => true,
            'label' => 'LLL:EXT:'.$extensionKey.'/Resources/Private/Language/locallang.xlf:mosparo_privatekey.label',
            'description' => 'LLL:EXT:'.$extensionKey.'/Resources/Private/Language/locallang.xlf:mosparo_privatekey.description',
            'config' => [
                'description' => '',
                'type' => 'input',
                'size' => 30,
                'max' => 255,
                'eval' => 'trim',
            ]
        ],
        // TODO: implementation
        'mosparo_options' => [
            'exclude' => true,
            'label' => 'LLL:EXT:'.$extensionKey.'/Resources/Private/Language/locallang.xlf:mosparo_options.label',
            'description' => 'LLL:EXT:'.$extensionKey.'/Resources/Private/Language/locallang.xlf:mosparo_options.description',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'max' => 255,
                'eval' => 'trim',
            ]
        ],
    ];

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns(
        'tx_powermail_domain_model_field',
        $tempColumns
    );

    $GLOBALS['TCA']['tx_powermail_domain_model_field']['types']['spam_mosparo'] = [
        'showitem' => '
            title,
            type,
            mosparo_host,
            mosparo_uuid,
            mosparo_publickey,
            mosparo_privatekey,
            --div--;LLL:EXT:powermail/Resources/Private/Language/locallang_db.xlf:tx_powermail_domain_model_field.sheet1,
                mandatory,
                --palette--;Layout;43,
                --palette--;LLL:EXT:powermail/Resources/Private/Language/locallang_db.xlf:tx_powermail_domain_model_field.marker_title;5,
            --div--;LLL:EXT:powermail/Resources/Private/Language/locallang_db.xlf:tabs.access,
                sys_language_uid,
                l10n_parent,
                l10n_diffsource,
                hidden,
                starttime,
                endtime
        ',
        'columnsOverrides' => [...$tempColumns]
    ];

}, 'hh_powermail_mosparo');
