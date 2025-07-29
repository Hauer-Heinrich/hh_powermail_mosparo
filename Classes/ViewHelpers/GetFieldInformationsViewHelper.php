<?php
namespace HauerHeinrich\HhPowermailMosparo\ViewHelpers;

/***************************************************************
 * Copyright notice
 *
 * (c) 2025 Christian Hackl <hackl.chris@googlemail.com>
 * All rights reserved
 *
 * This script is part of the TYPO3 project. The TYPO3 project is
 * free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * The GNU General Public License can be found at
 * http://www.gnu.org/copyleft/gpl.html.
 *
 * This script is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * This copyright notice MUST APPEAR in all copies of the script!
 * Example
 * <html xmlns:f="http://typo3.org/ns/TYPO3/CMS/Fluid/ViewHelpers"
 *   xmlns:mosparo="http://typo3.org/ns/HauerHeinrich/HhPowermailMosparo/ViewHelpers"
 *   data-namespace-typo3-fluid="true">
 *
 *  <mosparo:getFieldInformations field="34" as="myResultName" />
 *  <f:debug>{myResultName}</f:debug>
 *  or
 *  <mosparo:getFieldInformations field="{field.uid}" />
 *  <f:debug>{fieldInformation}</f:debug>
 *  <f:comment>"fieldInformation" = fallback if no "as"-attribute is given</f:comment>
 */

// use \TYPO3\CMS\Extbase\Utility\DebuggerUtility;
use \TYPO3\CMS\Core\Database\Connection;
use \TYPO3\CMS\Core\Database\ConnectionPool;
use \TYPO3\CMS\Core\Utility\GeneralUtility;
use \TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use \TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;


class GetFieldInformationsViewHelper extends AbstractViewHelper {
    public function initializeArguments() {
        $this->registerArgument('as', 'string', 'The name of the variable which contains the result', false, 'fieldInformation');
        $this->registerArgument('field', 'string', 'Field UID', true);
    }

    /**
     * @param array $arguments
     * @param \Closure $renderChildrenClosure
     * @param RenderingContextInterface $renderingContext
     *
     * @return string
     */
    public static function renderStatic(array $arguments, \Closure $renderChildrenClosure, RenderingContextInterface $renderingContext) {
        $templateVariableContainer = $renderingContext->getVariableProvider();

        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tx_powermail_domain_model_field');

        $whereExpressions = [];
        $whereExpressions[] = $queryBuilder->expr()->eq('uid', $queryBuilder->createNamedParameter(\intval($arguments['field']), Connection::PARAM_STR));

        $queryBuilder
            ->select('*')
            ->from('tx_powermail_domain_model_field');
        $queryBuilder->where(...$whereExpressions);
        $results = $queryBuilder->executeQuery()->fetchAssociative();

        $output = '';
        $templateVariableContainer->add($arguments['as'], $results);
        $output .= $renderChildrenClosure();

        return $output;
    }
}
