<?php
/**
* @author         Pierre-Henry Soria <pierrehenrysoria@gmail.com>
* @copyright      (c) 2017, Pierre-Henry Soria. All Rights Reserved.
* @license        GNU General Public License; <https://www.gnu.org/licenses/gpl-3.0.en.html>
 */

declare(strict_types=1);

namespace PH7\Eu\Vat;

use SoapClient;
use SoapFault;
use stdClass;

class Validator implements Validatable
{
    const EU_VAT_API = 'http://ec.europa.eu/taxation_customs/vies/checkVatService.wsdl';

    private $sVatNumber;
    private $sCountryCode;
    private $oClient;

    /**
     * @param int|string $sVatNumber The VAT number
     * @param string $sCountryCode The country code
     * @throws SoapFault
     */
    public function __construct($sVatNumber, string $sCountryCode)
    {
        try {
            $this->oClient = new SoapClient(self::EU_VAT_API);
        } catch(SoapFault $oExcept) {
            exit('Impossible to connect to the europa SOAP  : ' . $oExcept->faultstring);
        }

        $this->sVatNumber = $sVatNumber;
        $this->sCountryCode = $sCountryCode;

        $this->sanitize();
    }

    /**
     * Check if the VAT number is valid or not
     *
     * @return bool
     */
    public function check(): bool
    {
        $oResponse = $this->sendRequest();
        return (bool) $oResponse->valid;
    }

    public function getName(): string
    {
        $oResponse = $this->sendRequest();
        return $oResponse->name ?? '';
    }

    public function getAddress(): string
    {
        $oResponse = $this->sendRequest();
        return $this->removeNewLines($oResponse->address) ?? '';
    }

    public function getRequestDate(): string
    {
        $oResponse = $this->sendRequest();
        return $oResponse->requestDate ?? '';
    }

    public function getCountryCode(): string
    {
        $oResponse = $this->sendRequest();
        return $oResponse->countryCode ?? '';
    }

    public function getVatNumber(): string
    {
        $oResponse = $this->sendRequest();
        return $oResponse->vatNumber ?? '';
    }

    public function sanitize()
    {
        $aSearch = [$this->sCountryCode, '-', '_', '.', ',', ' '];
        $this->sVatNumber = trim(str_replace($aSearch, '', $this->sVatNumber));
    }

    protected function removeNewLines(string $sString): string
    {
        return str_replace(["\n", "\r\n"], ', ', $sString);
    }

    /**
     * Send the VAT number and country code to europa.eu API.
     *
     * @return stdClass The VAT number's details.
     * @throws SoapFault
     * @throws Exception
     */
    protected function sendRequest(): stdClass
    {
        try {
            $aDetails = [
                'countryCode' => strtoupper($this->sCountryCode),
                'vatNumber' => $this->sVatNumber
            ];
            return $this->oClient->checkVat($aDetails);
        } catch(SoapFault $oExcept) {
            //trigger_error('Impossible to retrieve the VAT details: ' . $oExcept->faultstring);
            throw new Exception('Impossible to retrieve the VAT details: ' . $oExcept->faultstring);
            return new stdClass;
        }
    }
}
