<?php declare(strict_types=1);
/**
 * @author      pfroch <info@easySolutionsIT.de>
 * @link        http://easySolutionsIT.de
 * @copyright   e@sy Solutions IT 2020
 * @license     EULA
 * @package     fakertoolbox
 * @filesource  InternetTest.php
 * @version     1.0.0
 * @since       30.05.20 - 19:56
 */
namespace Esit\Fakertoolbox\Tests\Provider;

use Esit\Fakertoolbox\Classes\Exception\ArrayIsEmptyException;
use Esit\Fakertoolbox\Classes\Provider\Internet;
use Faker\Generator;
use PHPUnit\Framework\TestCase;

/**
 * Class InternetTest
 * @package Esit\Fakertoolbox\Tests\Provider
 */
class InternetTest extends TestCase
{


    public function testInternetAddressReturnsAddress(): void
    {
        $generator = $this->getMockBuilder(Generator::class)
                          ->disableOriginalConstructor()
                          ->addMethods(['randomElement', 'optional'])
                          ->onlyMethods(['__get'])
                          ->getMock();

        $generator->expects($this->exactly(2))
                  ->method('randomElement')
                  ->withConsecutive([['https://','http://']], [['www.']])
                  ->will($this->onConsecutiveCalls('https://', 'www.'));

        $generator->expects($this->once())
                  ->method('optional')
                  ->with(0.8, '')
                  ->willReturn($this->returnSelf());

        $generator->expects($this->once())
                  ->method('__get')
                  ->with('domainName')
                  ->willReturn('example.org');

        $internet   = new Internet($generator);
        $rtn        = $internet->internetAddress();
        $this->assertSame('https://www.example.org/', $rtn);
    }


    public function testInternetAddressThrowExceptionIfProtocolsIsEmpty(): void
    {
        $generator = $this->getMockBuilder(Generator::class)
                          ->disableOriginalConstructor()
                          ->getMock();
        $internet   = new Internet($generator);
        $this->expectException(ArrayIsEmptyException::class);
        $internet->internetAddress([]);
    }
}
