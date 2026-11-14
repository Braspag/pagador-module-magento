<?php

namespace Braspag\BraspagPagador\Test\Unit\Helper;

use Braspag\BraspagPagador\Helper\Validator;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Helper\Context;
use PHPUnit\Framework\TestCase;

class ValidatorTest extends TestCase
{
    private function createValidator($sanitize = true, $dictionary = 'Av.-Avenida')
    {
        $scopeConfig = $this->createMock(ScopeConfigInterface::class);
        $scopeConfig->method('getValue')->willReturnMap([
            [Validator::XML_PATH_SHOULD_SANITIZE, 'store', null, $sanitize],
            [Validator::XML_PATH_SANITIZE_DISTRICT_DICTIONARY, 'store', null, $dictionary],
        ]);

        $context = $this->createMock(Context::class);
        $context->method('getScopeConfig')->willReturn($scopeConfig);

        return new Validator($context);
    }

    public function testMultibyteDistrictWithinLimitIsUnchanged()
    {
        $district = 'Área de Desenvolvimento Econômico (Águas Claras)';

        $result = $this->createValidator()->sanitizeDistrict($district);

        $this->assertSame($district, $result);
        $this->assertTrue(mb_check_encoding($result, 'UTF-8'));
    }

    public function testLongDistrictKeepsLast50CharactersOnCharacterBoundary()
    {
        $district = 'Ááááááááá Éééééééé Íííííííí Óóóóóóóó Úúúúúúúú Çççççççç';

        $result = $this->createValidator()->sanitizeDistrict($district);

        $this->assertSame(50, mb_strlen($result));
        $this->assertSame(mb_substr($district, -50), $result);
        $this->assertTrue(mb_check_encoding($result, 'UTF-8'));
    }

    public function testLongAsciiDistrictKeepsLast50Characters()
    {
        $district = str_repeat('abcdefghi ', 6);

        $result = $this->createValidator()->sanitizeDistrict(trim($district));

        $this->assertSame(50, strlen($result));
        $this->assertSame(substr(trim($district), -50), $result);
    }

    public function testDistrictIsReturnedAsIsWhenSanitizeIsDisabled()
    {
        $district = str_repeat('Ç', 80);

        $this->assertSame($district, $this->createValidator(false)->sanitizeDistrict($district));
    }
}
