<?php
/**
 * @author         Pierre-Henry Soria <pierrehenrysoria@gmail.com>
 * @copyright      (c) 2017-2019, Pierre-Henry Soria. All Rights Reserved.
 * @license        GNU General Public License; <https://www.gnu.org/licenses/gpl-3.0.en.html>
 */

declare(strict_types=1);

namespace PH7\Eu\Vat\Provider;

use PH7\Eu\Vat\Exception;
use SoapClient;
use SoapFault;
use stdClass;

class Europa implements Providable
{
    const EU_VAT_API = 'http://ec.europa.eu/taxation_customs/vies/checkVatService.wsdl';
    const IMPOSSIBLE_CONNECT_API_MESSAGE = 'Impossible to connect to the Europa SOAP: %s';
    const IMPOSSIBLE_RETRIEVE_DATA_MESSAGE = 'Impossible to retrieve the VAT details: %s';

    /** @var SoapClient */
    private $oClient;

    /**
     * Europa Provider constructor
     *
     * @throws Exception
     */
    public function __construct()
    {
        try {
            $this->oClient = new SoapClient($this->getApiUrl());
        } catch (SoapFault $oExcept) {
            throw new Exception(
                sprintf(self::IMPOSSIBLE_CONNECT_API_MESSAGE, $oExcept->faultstring),
                0,
                $oExcept
            );
        }
    }

    public function getApiUrl(): string
    {
        return static::EU_VAT_API;
    }

    /**
     * Send the VAT number and country code to europa.eu API and get the data.
     *
     * @param int|string $sVatNumber The VAT number
     * @param string $sCountryCode The country code
     *
     * @return stdClass The VAT number's details.
     *
     * @throws Exception
     */
    public function getResource($sVatNumber, string $sCountryCode): stdClass
    {
        try {
            $aDetails = [
                'countryCode' => strtoupper($sCountryCode),
                'vatNumber' => $sVatNumber
            ];
            return $this->oClient->checkVat($aDetails);
        } catch (SoapFault $oExcept) {
            throw new Exception(
                sprintf(self::IMPOSSIBLE_RETRIEVE_DATA_MESSAGE, $oExcept->faultstring)
            );
        }
    }
}
