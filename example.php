<?php
/**
* @author         Pierre-Henry Soria <pierrehenrysoria@gmail.com>
* @copyright      (c) 2017, Pierre-Henry Soria. All Rights Reserved.
* @license        GNU General Public License; <https://www.gnu.org/licenses/gpl-3.0.en.html>
 */

require 'src/autoloader.php';

use PH7\Eu\Vat\Validator;
use PH7\Eu\Vat\Provider\Europa;

$oVatValidator = new Validator(new Europa, '0472429986', 'BE');

if ($oVatValidator->check()) {
    $sRequestDate = $oVatValidator->getRequestDate();
    // Optional, format the date
    $sFormattedRequestDate = (new DateTime)->format('d-m-Y');

    echo 'Business Name: ' . $oVatValidator->getName() . '<br />';
    echo 'Address: ' . $oVatValidator->getAddress() . '<br />';
    echo 'Request Date: ' . $sFormattedRequestDate . '<br />';
    echo 'Member State: ' . $oVatValidator->getCountryCode() . '<br />';
    echo 'VAT Number: ' . $oVatValidator->getVatNumber() . '<br />';
} else {
    echo 'Invalid VAT number';
}
