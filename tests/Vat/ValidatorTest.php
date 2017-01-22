<?php
/**
* @author         Pierre-Henry Soria <pierrehenrysoria@gmail.com>
* @copyright      (c) 2017, Pierre-Henry Soria. All Rights Reserved.
* @license        GNU General Public License; <https://www.gnu.org/licenses/gpl-3.0.en.html>
 */

declare(strict_types=1);

namespace PH7\Eu\Tests\Vat;

use PH7\Eu\Vat\Validator;
use PH7\Eu\Vat\Exception;
use PH7\Eu\Vat\Provider\Providable;
use PH7\Eu\Vat\Provider\Europa;
use Phake;
use SoapFault;
use stdClass;

class ValidatorTest extends \PHPUnit_Framework_TestCase
{
    protected function setUp()
    {

    }

    /**
     * @dataProvider validVatNumbers
     * @param int|string $sVatNumber The VAT number
     * @param string $sCountryCode The country code
     */
    public function testValidVatNumbers($sVatNumber, string $sCountryCode)
    {
        try {
            $oVatValidator = new Validator(new Europa, $sVatNumber, $sCountryCode);
            $this->assertTrue($oVatValidator->check());
        } catch (Exception $oExcept) {
            $this->assertIsResponseFailure($oExcept);
        }
    }

    /**
     * @dataProvider invalidVatNumbers
     * @param int|string $sVatNumber The VAT number
     * @param string $sCountryCode The country code
     */
    public function testInvalidVatNumbers($sVatNumber, string $sCountryCode)
    {
        try {
            $oVatValidator = new Validator(new Europa, $sVatNumber, $sCountryCode);
            $this->assertFalse($oVatValidator->check());
        } catch (Exception $oExcept) {
            $this->assertIsResponseFailure($oExcept);
        }
    }

    /**
     * @dataProvider validVatNumberDetails
     */
    public function testValidVatNumberStatus(stdClass $oVatDetails)
    {
        $oValidator = $this->setUpAndMock($oVatDetails);
        $this->assertTrue($oValidator->check());
    }

    /**
     * @dataProvider invalidVatNumberDetails
     */
    public function testInvalidVatNumberStatus(stdClass $oVatDetails)
    {
        $oValidator = $this->setUpAndMock($oVatDetails);
        $this->assertFalse($oValidator->check());
    }

    /**
     * @dataProvider validVatNumberDetails
     */
    public function testName(stdClass $oVatDetails)
    {
        $oValidator = $this->setUpAndMock($oVatDetails);
        $this->assertEquals('NV EXKI', $oValidator->getName());
    }

    /**
     * @dataProvider validVatNumberDetails
     */
    public function testAddress(stdClass $oVatDetails)
    {
        $oValidator = $this->setUpAndMock($oVatDetails);
        $this->assertEquals('ELSENSE STEENWEG 12, 1050 ELSENE', $oValidator->getAddress());
    }

    /**
     * @dataProvider validVatNumberDetails
     */
    public function testCountryCode(stdClass $oVatDetails)
    {
        $oValidator = $this->setUpAndMock($oVatDetails);
        $this->assertEquals('BE', $oValidator->getCountryCode());
    }

    /**
     * @dataProvider validVatNumberDetails
     */
    public function testVatNumber(stdClass $oVatDetails)
    {
        $oValidator = $this->setUpAndMock($oVatDetails);
        $this->assertEquals('0472429986', $oValidator->getVatNumber());
    }

    /**
     * @dataProvider validVatNumberDetails
     */
    public function testRequestDate(stdClass $oVatDetails)
    {
        $oValidator = $this->setUpAndMock($oVatDetails);
        $this->assertEquals('2017-01-22+01:00', $oValidator->getRequestDate());
    }

    public function testResource()
    {
        try {
            $oEuropaProvider = new Europa;
            $this->assertInstanceOf(stdClass::class, $oEuropaProvider->getResource('0472429986', 'BE'));
        } catch (Exception $oExcept) {
            $this->assertIsResponseFailure($oExcept);
        }
    }

    public function validVatNumbers(): array
    {
        $aData = [
            ['0472429986', 'BE'],
            ['9763375H', 'IE'],
            [29672050085, 'FR']
        ];

        return $aData;
    }

    public function invalidVatNumbers(): array
    {
        $aData = [
            [243852752, 'UK'], // Has to be 'GB'
            [29672050085, 'FRANCE'],
            ['blablabla', 'DE']
        ];

        return $aData;
    }

    public function validVatNumberDetails(): array
    {
        $oData = new stdClass;
        $oData->valid = true;
        $oData->countryCode = 'BE';
        $oData->vatNumber = '0472429986';
        $oData->requestDate = '2017-01-22+01:00';
        $oData->name = 'NV EXKI';
        $oData->address = 'ELSENSE STEENWEG 12, 1050 ELSENE';

        return [
            [$oData]
        ];
    }

    public function invalidVatNumberDetails(): array
    {
        $oData = new stdClass;
        $oData->valid = false;

        return [
            [$oData]
        ];
    }

    private function setUpAndMock(stdClass $oVatDetails): \Phake_IMock
    {
        $oProvider = Phake::mock(Providable::class);
        Phake::when($oProvider)->getResource(Phake::anyParameters())->thenReturn($oVatDetails);
        $oValidator = Phake::partialMock(Validator::class, $oProvider, '0472429986', 'BE');
        Phake::verify($oValidator)->sanitize();

        return $oValidator;
    }

    private function assertIsResponseFailure(Exception $oExecpt)
    {
        $this->assertRegexp('/^Impossible to retrieve the VAT details/' , $oExecpt->getMessage());
    }
}
