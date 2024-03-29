<?php
/**
 * @author         Pierre-Henry Soria <hi@pH7.me>
 * @copyright      (c) 2017-2023, Pierre-Henry Soria. All Rights Reserved.
 * @license        GNU General Public License; <https://www.gnu.org/licenses/gpl-3.0.en.html>
 */

require 'src/autoloader.php';

use PH7\Eu\Vat\Provider\Europa;
use PH7\Eu\Vat\Validator;

$sEuVatNumber = '0472429986'; // EU VAT number
$sEuCountryCode = 'BE'; // EU two-letter country code

$oVatValidator = new Validator(new Europa, $sEuVatNumber, $sEuCountryCode);

if ($oVatValidator->check()) {
    $sRequestDate = $oVatValidator->getRequestDate();
 
    // Optional - explicitly format the date to d-m-Y format
    $sFormattedRequestDate = (new DateTime)->format('d-m-Y');

    echo 'Business Name: ' . $oVatValidator->getName() . '<br />';
    echo 'Address: ' . $oVatValidator->getAddress() . '<br />';
    echo 'Request Date: ' . $sFormattedRequestDate . '<br />';
    echo 'Member State: ' . $oVatValidator->getCountryCode() . '<br />';
    echo 'VAT Number: ' . $oVatValidator->getVatNumber() . '<br />';
} else {
    echo 'Invalid VAT number';
}
