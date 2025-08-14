<?php
declare(strict_types=1);

return [
    // \HauerHeinrich\HhPowermailMosparo\Domain\Model\Field::class => [
    //     'tableName' => 'tx_powermail_domain_model_field',
    // ],
    // \In2code\Powermail\Domain\Model\Field::class => [
    //     'subclasses' => [
    //         \HauerHeinrich\HhPowermailMosparo\Domain\Model\Field::class,
    //     ],
    // ],

    // \In2code\Powermail\Domain\Model\Field::class => [
    //     'className' => \HauerHeinrich\HhPowermailMosparo\Domain\Model\Field::class,
    //     'tableName' => 'tx_powermail_domain_model_field',
    // ],

    \In2code\Powermail\Domain\Model\Field::class => [
        'subclasses' => [
            \HauerHeinrich\HhPowermailMosparo\Domain\Model\Field::class,
        ],
    ],
    \HauerHeinrich\HhPowermailMosparo\Domain\Model\Field::class => [
        'tableName' => 'tx_powermail_domain_model_field',
    ],
    \In2code\Powermail\Domain\Repository\FieldRepository::class => [
        'className' => \HauerHeinrich\HhPowermailMosparo\Domain\Repository\FieldRepository::class,
    ],
];
