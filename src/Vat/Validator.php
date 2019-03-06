<?php
/**
 * @author         Pierre-Henry Soria <pierrehenrysoria@gmail.com>
 * @copyright      (c) 2017-2019, Pierre-Henry Soria. All Rights Reserved.
 * @license        GNU General Public License; <https://www.gnu.org/licenses/gpl-3.0.en.html>
 */

declare(strict_types=1);

namespace PH7\Eu\Vat;

use PH7\Eu\Vat\Provider\Providable;
use stdClass;

class Validator implements Validatable
{
    /** @var int|string */
    private $sVatNumber;

    /** @var string */
    private $sCountryCode;

    /** @var stdClass */
    private $oResponse;

    /**
     * @param Providable $oProvider The API that checks the VAT no. and retrieve the VAT registration's details.
     * @param int|string $sVatNumber The VAT number.
     * @param string $sCountryCode The country code.
     */
    public function __construct(Providable $oProvider, $sVatNumber, string $sCountryCode)
    {
        $this->sVatNumber = $sVatNumber;
        $this->sCountryCode = $sCountryCode;

        $this->sanitize();
        $this->oResponse = $oProvider->getResource($this->sVatNumber, $this->sCountryCode);
    }

    /**
     * Check if the VAT number is valid or not
     *
     * @return bool
     */
    public function check(): bool
    {
        return (bool)$this->oResponse->valid;
    }

    public function getName(): string
    {
        return $this->oResponse->name ?? '';
    }

    public function getAddress(): string
    {
        return $this->cleanAddress($this->oResponse->address) ?? '';
    }

    public function getRequestDate(): string
    {
        return $this->oResponse->requestDate ?? '';
    }

    public function getCountryCode(): string
    {
        return $this->oResponse->countryCode ?? '';
    }

    public function getVatNumber(): string
    {
        return $this->oResponse->vatNumber ?? '';
    }

    public function sanitize()
    {
        $aSearch = [$this->sCountryCode, '-', '_', '.', ',', ' '];
        $this->sVatNumber = trim(str_replace($aSearch, '', $this->sVatNumber));
        $this->sCountryCode = strtoupper($this->sCountryCode);
    }

    protected function cleanAddress(string $sString): string
    {
        return trim(str_replace(["\n", "\r\n"], ', ', $sString), ', ');
    }
}
