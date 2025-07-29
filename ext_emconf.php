<?php

/***************************************************************
 * Extension Manager/Repository config file for ext "hh_powermail_mosparo".
 *
 * Auto generated 11-06-2025 14:19
 *
 * Manual updates:
 * Only the data in the array - everything else is removed by next
 * writing. "version" and "dependencies" must not be touched!
 ***************************************************************/

$EM_CONF['hh_powermail_mosparo'] = [
    'title' => 'Hauer-Heinrich - "Mosparo.io" SPAM protection system',
    'description' => 'Additional field for EXT:powermail. Adds "Mosparo.io" SPAM protection system.',
    'category' => 'plugin',
    'version' => '0.1.0',
    'state' => 'stable',
    'uploadfolder' => false,
    'clearcacheonload' => false,
    'author' => 'Christian Hackl',
    'author_email' => 'web@hauer-heinrich.de',
    'author_company' => '',
    'constraints' => [
        'depends' => [
            'php' => '>=8.2.0',
            'typo3' => '12.4.0-13.4.99',
            'extbase' => '12.4.0-13.4.99',
            'fluid' => '12.4.0-13.4.99',
            'powermail' => '8.0.0-13.0.99',
        ],
      'conflicts' => [
      ],
      'suggests' => [
      ],
    ],
    'autoload' => [
        'psr-4' => [
            'HauerHeinrich\\HhPowermailCheckboxlink\\' => 'Classes',
        ],
    ],
];
