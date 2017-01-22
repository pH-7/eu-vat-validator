<?php
/**
* @author         Pierre-Henry Soria <pierrehenrysoria@gmail.com>
* @copyright      (c) 2017, Pierre-Henry Soria. All Rights Reserved.
* @license        GNU General Public License; <https://www.gnu.org/licenses/gpl-3.0.en.html>
 */

declare(strict_types=1);

namespace PH7\Eu\Tests\Vat\Provider;

use PH7\Eu\Vat\Provider\Europa;

class ProviderTest extends \PHPUnit_Framework_TestCase
{
    private $oEuropa;

    protected function setUp()
    {
        $this->oEuropa = new Europa;
    }

    public function testApiUrl()
    {
        $this->assertEquals(Europa::EU_VAT_API, $this->oEuropa->getApiUrl());
    }
}
