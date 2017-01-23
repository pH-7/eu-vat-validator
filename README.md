# EU VAT Number Validator

A simple and clean PHP class that validates EU VAT numbers against the central ec.europa.eu database (using the official europa API).


## The Problem

Validate VAT numbers might be difficult and if you use a validation pattern to check if the format is valid, you are never sure if the VAT registration number is still valid.

## The Solution

This [PHP VAT validator library](https://github.com/pH-7/eu-vat-validator) uses real-time data feeds from individual EU member states' VAT systems so you are sure of the validity of the number and avoid fraud with expired or wrong VAT numbers.

For example, this kind of validation can be very useful on online payment forms.


## Composer Installation

* Be sure PHP 7.0 or higher is installed

* Install Composer (https://getcomposer.org)

* Then, include it in your project:

```bash
composer require ph-7/eu-vat-validator
 ```

* Then, include Composer's autoload (if not already done in your project)

 ```php
require_once 'vendor/autoload.php';
```


## Manual Installation (*the old-fashion way*)

If you don't use Composer, you can install it without Composer by including the following

```php
require 'src/autoloader.php';
```


## How to Use

### Example

```php
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
```


## Requirements

* PHP 7.0 or higher
* [Composer](https://getcomposer.org)
* [SOAPClient](http://php.net/manual/en/class.soapclient.php) PHP Extension enabled


## About Me

I'm **Pierre-Henry Soria**, a passionate Software Engineer and the creator of [pH7CMS](https://github.com/pH7Software/pH7-Social-Dating-CMS).


## Where to Contact Me?

You can by email at **pierrehenrysoria+github [[AT]] gmail [[D0T]] com**


## References

[VAT Information Exchange System (VIES)](http://ec.europa.eu/taxation_customs/vies/)


## License

Under [General Public License 3](http://www.gnu.org/licenses/gpl.html) or later.
