<?php
declare(strict_types=1);
namespace HauerHeinrich\HhPowermailMosparo\Domain\Validator\SpamShield;

// use \TYPO3\CMS\Extbase\Utility\DebuggerUtility;
use \Psr\Log\LoggerInterface;
use \In2code\Powermail\Domain\Model\Field;
use \In2code\Powermail\Domain\Validator\SpamShield\AbstractMethod;
use \TYPO3\CMS\Core\Log\LogManager;
use \TYPO3\CMS\Core\Utility\ArrayUtility;
use \TYPO3\CMS\Core\Utility\GeneralUtility;
use \TYPO3\CMS\Core\Database\Connection;
use \TYPO3\CMS\Core\Database\ConnectionPool;

class MosparoMethod extends AbstractMethod {

    private LoggerInterface $logger;
    protected string $mosparoHost;
    protected string $mosparoPublicKey;
    private string $mosparoPrivateKey;

    /**
     * Check if secret key is given and set it
     *
     * @return void
     * @throws \Exception
     */
    public function initialize(): void {
        $this->logger = GeneralUtility::makeInstance(LogManager::class)->getLogger(__CLASS__);

        $formMosparoField = $this->isFormWithMosparoField();

        if($formMosparoField !== NULL) {
            $mosparoField = $this->getRawField(intval($formMosparoField->getUid()));
            if(
                empty($this->configuration['privatekey'])
                && (
                    empty($mosparoField)
                    || empty($mosparoField['mosparo_privatekey'])
                )
            ) {
                throw new \LogicException(
                    'No privatekey given. Please add a secret key to TypoScript Constants or to the form field!',
                    1607012762
                );
            }

            $this->mosparoPrivateKey = isset($mosparoField['mosparo_privatekey']) ? trim($mosparoField['mosparo_privatekey']) : $this->configuration['privatekey'];
            $this->mosparoHost = isset($mosparoField['mosparo_host']) ? trim($mosparoField['mosparo_host']) : $this->configuration['host'];
            $this->mosparoPublicKey = isset($mosparoField['mosparo_publickey']) ? trim($mosparoField['mosparo_publickey']) : $this->configuration['publickey'];
        }
    }

    /**
     * @return bool true if spam recognized
     */
    public function spamCheck(): bool {
        if ($this->isFormWithMosparoField() !== NULL && $this->isCaptchaCheckToSkip()) {
            return false;
        }

        $formData = $GLOBALS['TYPO3_REQUEST']->getParsedBody() ?? [];
        $formData = $this->flattenArrayWithBracketKeys($formData);

        return !$this->isValid($formData);
    }

    protected function isValid(array $formData): bool {
        try {
            // 1. Remove the ignored fields from the form data
            // You have to do this only if you have ignored fields in your form

            // 2. Extract the submit and validation token from the form data
            $submitToken = $GLOBALS['TYPO3_REQUEST']->getParsedBody()['_mosparo_submitToken'] ?? '';
            $validationToken = $GLOBALS['TYPO3_REQUEST']->getParsedBody()['_mosparo_validationToken'] ?? '';

            $preparedFormData = [];
            foreach ($formData as $fieldName => $value) {
                if (str_starts_with($fieldName, '_mosparo_')) {
                    continue;
                }

                // Currently mosparo does not support brackets in field names, we also remove the powermail field prefix
                $fieldName = str_replace(['tx_powermail_pi1[field]', '[', ']'], '', $fieldName);

                $preparedFormData[$fieldName] = str_replace("\r\n", "\n", $value);
            }

            // 4. Generate the hashes
            foreach ($preparedFormData as $fieldName => $value) {
                $preparedFormData[$fieldName] = hash('sha256', $value);
            }

            ksort($preparedFormData);

            // 5. Generate the form data signature
            $jsonPreparedFormData = json_encode($preparedFormData, JSON_THROW_ON_ERROR);
            $formDataSignature = hash_hmac('sha256', $jsonPreparedFormData, $this->mosparoPrivateKey);

            // 6. Generate the validation signature
            $validationSignature = hash_hmac('sha256', $validationToken, $this->mosparoPrivateKey);

            // 7. Prepare the verification signature
            $combinedSignatures = $validationSignature . $formDataSignature;
            $verificationSignature = hash_hmac('sha256', $combinedSignatures, $this->mosparoPrivateKey);

            // 8. Collect the request data
            $apiEndpoint = '/api/v1/verification/verify'; // This is the API of mosparo, so it's a fixed value
            $requestData = [
                'submitToken' => $submitToken,
                'validationSignature' => $validationSignature,
                'formSignature' => $formDataSignature,
                'formData' => $preparedFormData,
            ];

            // 9. Generate the request signature
            $jsonRequestData = json_encode($requestData, JSON_THROW_ON_ERROR);
            $combinedApiEndpointJsonRequestData = $apiEndpoint . $jsonRequestData;
            $requestSignature = hash_hmac('sha256', $combinedApiEndpointJsonRequestData, $this->mosparoPrivateKey);

            // 10. Send the API request
            $ch = curl_init($this->mosparoHost . $apiEndpoint);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($requestData));
            curl_setopt($ch, CURLOPT_USERPWD, $this->mosparoPublicKey . ':' . $requestSignature);
            $response = curl_exec($ch);
            if ($response === false) {
                return false;
            }

            // 11. Check the response
            $responseData = json_decode($response, true, 512, JSON_THROW_ON_ERROR);
            curl_close($ch);

            if(
                isset($responseData['valid'], $responseData['verificationSignature'])
                && $responseData['valid']
                && $responseData['verificationSignature'] === $verificationSignature
            ) {
                return true;
            }

            if(isset($this->configuration['debug']) && !empty($this->configuration['debug']) && is_array($responseData)) {
                $this->logger->debug('Mosparo spam detected on ', [
                    'Class' => 'HauerHeinrich\\Domain\\Validator\\SpamShield\\MosparoMethod',
                    'method' => 'isValid()',
                    'Line' => 140,
                    'data' => [...$responseData]
                ]);
            }

            return false;
        } catch (\Throwable $e) {
            $this->logger->error('Mosparo Error on ', [
                'Class' => 'HauerHeinrich\\Domain\\Validator\\SpamShield\\MosparoMethod',
                'method' => 'isValid()',
                'Line' => 152,
                'error' => [
                    $e->getMessage(),
                    $e->getTraceAsString(),
                    $_SERVER['REQUEST_URI'],
                ],
            ]);

            return false;
        }
    }

    /**
     * Check if current form has a mosparo field
     */
    protected function isFormWithMosparoField(): \In2code\Powermail\Domain\Model\Field|null {
        try {
            foreach ($this->mail->getForm()?->getPages() as $page) {
                /** @var Field $field */
                foreach ($page->getFields() as $field) {
                    if ($field->getType() === 'spam_mosparo') {
                        return $field;
                    }
                }
            }
        } catch (\Throwable) {}

        return NULL;
    }


    /**
     * Captcha check should be skipped on createAction if there was a confirmationAction where the captcha was
     * already checked before
     * Note: $this->flexForm is only available in powermail 3.9 or newer
     */
    protected function isCaptchaCheckToSkip(): bool {
        if (property_exists($this, 'flexForm')) {
            $action = $this->getActionName();
            $confirmationActive = $this->flexForm['settings']['flexform']['main']['confirmation'] === '1';
            $optinActive = $this->flexForm['settings']['flexform']['main']['optin'] === '1';

            if (($action === 'create' && $confirmationActive) || ($action === 'checkCreate' && $confirmationActive)) {
                return true;
            }

            if ($action === 'optinConfirm' && $optinActive) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return string "confirmation" or "create"
     */
    protected function getActionName(): string {
        $request = $GLOBALS['TYPO3_REQUEST'];
        $pluginVariables = $request->getQueryParams()['tx_powermail_pi1'];
        ArrayUtility::mergeRecursiveWithOverrule($pluginVariables, $request->getParsedBody()['tx_powermail_pi1']);

        return $pluginVariables['action'];
    }

    protected function flattenArrayWithBracketKeys(array $array, string $prefix = ''): array {
        $result = [];
        foreach ($array as $key => $value) {
            $newKey = $prefix === '' ? $key : $prefix . '[' . $key . ']';
            if (is_array($value)) {
                $result += $this->flattenArrayWithBracketKeys($value, $newKey);
            } else {
                $result[$newKey] = $value;
            }
        }

        return $result;
    }

    /**
     * get raw tx_powermail_domain_model_field
     *
     * @param  integer $uid - uid of the field
     * @return array
     */
    protected function getRawField(int $uid): array {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tx_powermail_domain_model_field');

        $whereExpressions = [];
        $whereExpressions[] = $queryBuilder->expr()->eq('uid', $queryBuilder->createNamedParameter($uid, Connection::PARAM_INT));

        $queryBuilder
            ->select('*')
            ->from('tx_powermail_domain_model_field');
        $queryBuilder->where(...$whereExpressions);

        return $queryBuilder->executeQuery()->fetchAssociative();
    }
}
