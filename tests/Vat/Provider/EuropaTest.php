<?php
/**
 * @author         Pierre-Henry Soria <hi@ph7.me>
 * @copyright      (c) 2017-2021, Pierre-Henry Soria. All Rights Reserved.
 * @license        GNU General Public License; <https://www.gnu.org/licenses/gpl-3.0.en.html>
 */

declare(strict_types=1);

namespace PH7\Eu\Tests\Vat\Provider;

use PH7\Eu\Vat\Provider\Europa;
use PHPUnit\Framework\TestCase;

class ProviderTest extends TestCase
{
    /** @var Europa */
    private $oEuropa;

    protected function setUp(): void
    {
        $this->oEuropa = new Europa;
    }

    public function testApiUrl(): void
    {
        $this->assertEquals(Europa::EU_VAT_API . Europa::EU_VAT_WSDL_ENDPOINT, $this->oEuropa->getApiUrl());
    }
}
