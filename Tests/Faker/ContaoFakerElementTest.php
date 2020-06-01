<?php declare(strict_types=1);
/**
 * @author      pfroch <info@easySolutionsIT.de>
 * @link        http://easySolutionsIT.de
 * @copyright   e@sy Solutions IT 2020
 * @license     EULA
 * @package     fakertoolbox
 * @filesource  ContaoFakerElementTest.php
 * @version     1.0.0
 * @since       24.05.20 - 12:52
 */
namespace Esit\Fakertoolbox\Tests\Faker;

use Esit\Fakertoolbox\Classes\Exception\ArrayIsEmptyException;
use Esit\Fakertoolbox\Classes\Exception\LocalStringIsEmptyException;
use Esit\Fakertoolbox\Classes\Faker\ContaoFakerElement;
use Esit\Fakertoolbox\Classes\Faker\DcaExtractor;
use Faker\Provider\Base;
use PHPUnit\Framework\TestCase;
use Faker\Generator;

class ContaoFakerElementTest extends TestCase
{


    /**
     * @var Generator
     */
    protected $faker;


    /**
     * @var DcaExtractor
     */
    protected $extractor;


    protected function setUp(): void
    {
        $this->faker        = $this->getMockBuilder(Generator::class)
                                   ->disableOriginalConstructor()
                                   ->addMethods(['optional', 'firstname', 'unique', 'numberBetween'])
                                   ->onlyMethods(['addProvider', 'seed'])
                                   ->getMock();
        $this->extractor    = $this->getMockBuilder(DcaExtractor::class)
                                   ->disableOriginalConstructor()
                                   ->onlyMethods([
                                       'getFakerMethod',
                                       'getFakerArguments',
                                       'getFakerOptional',
                                       'getFakerSerial',
                                       'getFakerUnique'])
                                   ->getMock();
    }


    public function testGetThrowsExceptionIfLocalStringIsEmpty(): void
    {
        $this->expectException(LocalStringIsEmptyException::class);
        new ContaoFakerElement($this->extractor, '');
    }


    public function testGetCallFakerMethod(): void
    {
        $fielname   = 'name';
        $expected   = 'Martin';
        $this->extractor->expects($this->once())->method('getFakerMethod')->with($fielname)->willReturn('firstname');
        $this->extractor->expects($this->once())->method('getFakerArguments')->with($fielname)->willReturn([]);
        $this->extractor->expects($this->once())->method('getFakerOptional')->with($fielname)->willReturn([]);
        $this->extractor->expects($this->once())->method('getFakerUnique')->with($fielname)->willReturn(false);
        $this->faker->expects($this->once())->method('firstname')->willReturn($expected);
        $element = new ContaoFakerElement($this->extractor);
        $element->setFaker($this->faker);
        $rtn = $element->get($fielname);
        $this->assertSame($expected, $rtn);
    }


    public function testGetCallFakerOptional(): void
    {
        $fielname   = 'name';
        $expected   = 'Martin';
        $this->extractor->expects($this->once())->method('getFakerMethod')->with($fielname)->willReturn('firstname');
        $this->extractor->expects($this->once())->method('getFakerArguments')->with($fielname)->willReturn([]);
        $this->extractor->expects($this->once())->method('getFakerOptional')->with($fielname)->willReturn([1, 2]);
        $this->extractor->expects($this->once())->method('getFakerUnique')->with($fielname)->willReturn(false);
        $this->faker->expects($this->once())->method('firstname')->willReturn($expected);
        $this->faker->expects($this->once())->method('optional')->with(1,2)->willReturn($this->returnSelf());
        $element = new ContaoFakerElement($this->extractor);
        $element->setFaker($this->faker);
        $rtn = $element->get($fielname);
        $this->assertSame($expected, $rtn);
    }


    public function testGetCallFakerUnique(): void
    {
        $fielname   = 'name';
        $expected   = 'Martin';
        $this->extractor->expects($this->once())->method('getFakerMethod')->with($fielname)->willReturn('firstname');
        $this->extractor->expects($this->once())->method('getFakerArguments')->with($fielname)->willReturn([]);
        $this->extractor->expects($this->once())->method('getFakerOptional')->with($fielname)->willReturn([]);
        $this->extractor->expects($this->once())->method('getFakerUnique')->with($fielname)->willReturn(true);
        $this->faker->expects($this->once())->method('firstname')->willReturn($expected);
        $this->faker->expects($this->once())->method('unique')->willReturn($expected);
        $element = new ContaoFakerElement($this->extractor);
        $element->setFaker($this->faker);
        $rtn = $element->get($fielname);
        $this->assertSame($expected, $rtn);
    }


    public function testGetThrowExceptionIfSerialIsNotAnArrayWithTwoElements(): void
    {
        $fielname = 'name';
        $this->extractor->expects($this->once())->method('getFakerSerial')->with($fielname)->willReturn([1]);
        $this->faker->expects($this->never())->method('numberBetween');
        $element = new ContaoFakerElement($this->extractor);
        $element->setFaker($this->faker);
        $this->expectException(ArrayIsEmptyException::class);
        $this->expectExceptionMessage('you have to set serial[min, max]');
        $element->get($fielname);
    }


    public function testGetCallSerial(): void
    {
        $count      = 3;
        $fielname   = 'name';
        $expected   = 'Martin';
        $this->extractor->expects($this->exactly($count))->method('getFakerMethod')->with($fielname)->willReturn('firstname');
        $this->extractor->expects($this->exactly($count))->method('getFakerArguments')->with($fielname)->willReturn([]);
        $this->extractor->expects($this->exactly($count))->method('getFakerOptional')->with($fielname)->willReturn([]);
        $this->extractor->expects($this->once())->method('getFakerSerial')->with($fielname)->willReturn([1,5]);
        $this->extractor->expects($this->exactly($count))->method('getFakerUnique')->with($fielname)->willReturn(false);
        $this->faker->expects($this->once())->method('numberBetween')->with(1,5)->willReturn($count);
        $this->faker->expects($this->exactly($count))->method('firstname')->willReturn($expected);
        $this->faker->expects($this->never())->method('optional');
        $element = new ContaoFakerElement($this->extractor);
        $element->setFaker($this->faker);
        $rtn = $element->get($fielname);
        $this->assertSame(\serialize([$expected, $expected, $expected]), $rtn);
    }


    public function testGetReturnEmtpyStringIfOptional(): void
    {
        $count      = 0;
        $fielname   = 'name';
        $expected   = 'Martin';
        $this->extractor->expects($this->exactly($count))->method('getFakerMethod')->with($fielname)->willReturn('firstname');
        $this->extractor->expects($this->exactly($count))->method('getFakerArguments')->with($fielname)->willReturn([]);
        $this->extractor->expects($this->exactly($count))->method('getFakerOptional')->with($fielname)->willReturn([]);
        $this->extractor->expects($this->once())->method('getFakerSerial')->with($fielname)->willReturn([0,5]);
        $this->extractor->expects($this->exactly($count))->method('getFakerUnique')->with($fielname)->willReturn(false);
        $this->faker->expects($this->once())->method('numberBetween')->with(0,5)->willReturn($count);
        $this->faker->expects($this->never())->method('firstname');
        $this->faker->expects($this->never())->method('optional');
        $element = new ContaoFakerElement($this->extractor);
        $element->setFaker($this->faker);
        $rtn = $element->get($fielname);
        $this->assertSame('', $rtn);
    }


    public function testAddProviderCallsFakerAddProvider(): void
    {
        $element    = new ContaoFakerElement($this->extractor);
        $test       = new ProviderTestClass($this->faker);
        $this->faker->expects($this->once())->method('addProvider')->with($test);
        $element->setFaker($this->faker);
        $element->addProvider(ProviderTestClass::class);
    }


    public function testSeedCallsFakerSeed(): void
    {
        $this->faker->expects($this->once())->method('seed')->with(12);
        $element = new ContaoFakerElement($this->extractor);
        $element->setFaker($this->faker);
        $element->seed(12);
    }
}

class ProviderTestClass extends Base{}