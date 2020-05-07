<?php


namespace Misc;

use PHPUnit\Framework\TestCase;
use Tarre\Php46Elks\Exceptions\InvalidE164PhoneNumberFormatException;
use Tarre\Php46Elks\Exceptions\InvalidSenderIdException;
use Tarre\Php46Elks\Utils\Helper;


final class HelperTest extends TestCase
{

    public function testGetUrlDefault()
    {
        $this->assertNull(Helper::getBaseUrl());
    }

    /**
     * @depends testGetUrlDefault
     */
    public function testSetBaseUrl()
    {
        Helper::setBaseUrl('http://localhost');
        $this->assertTrue(true);
    }

    /**
     * @depends testSetBaseUrl
     */
    public function testGetBaseUrl()
    {
        $this->assertSame('http://localhost', Helper::getBaseUrl());
    }

    /**
     * @depends testSetBaseUrl
     */
    public function testGenerateUrl()
    {
        $this->assertSame('http://localhost', Helper::url(''));
    }

    /**
     * @depends testSetBaseUrl
     */
    public function testGenerateUrlWithParams()
    {
        $this->assertSame('http://localhost?param1=a&param2=b', Helper::url('', [
            'param1' => 'a',
            'param2' => 'b',
        ]));
    }

    /**
     * @depends testSetBaseUrl
     */
    public function testGenerateUrlWithDefaultParams()
    {
        Helper::setBaseUrl('http://localhost', [
            'param1' => 1,
            'param2' => 'b',
            'param3' => 'c',
        ]);


        $this->assertSame('http://localhost?param1=a&param2=b&param3=c', Helper::url('', [
            'param1' => 'a',
            'param2' => 'b',
        ]));

        // restore

        Helper::setBaseUrl('http://localhost');
    }

    /**
     * @depends testSetBaseUrl
     */
    public function testGenerateUrlWithOtherUrl()
    {
        $this->assertSame('http://somethingelse', Helper::url('http://somethingelse'));
    }

    /**
     * @depends testSetBaseUrl
     */
    public function testGenerateUrlWithRelativeName()
    {
        Helper::setBaseUrl('http://localhost');

        $this->assertSame('http://localhost/imRelative', Helper::url('imRelative'));
    }

    public function testValidateE164PhoneNumber()
    {
        try {
            Helper::validateE164PhoneNumber('+4670144412');
            $this->assertTrue(true);
        } catch (InvalidE164PhoneNumberFormatException $exception) {
            $this->assertTrue(false);
        }
    }

    public function testValidateSenderID()
    {
        try {
            Helper::validateSenderID('ABC123');
            Helper::validateSenderID('+4670123213');
            $this->assertTrue(true);
        } catch (InvalidSenderIdException $exception) {
            $this->assertTrue(false);
        }
    }
}
