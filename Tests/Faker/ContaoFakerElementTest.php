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
                                   ->addMethods(['optional', 'firstname', 'unique'])
                                   ->onlyMethods(['addProvider', 'seed'])
                                   ->getMock();
        $this->extractor    = $this->getMockBuilder(DcaExtractor::class)
                                   ->disableOriginalConstructor()
                                   ->onlyMethods([
                                       'getFakerMethod',
                                       'getFakerArguments',
                                       'getFakerOptional',
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


    public function testAddProviderCallsFakerAddProvider(): void
    {
        $base       = $this->getMockBuilder(Base::class)->disableOriginalConstructor()->getMock();
        $element    = new ContaoFakerElement($this->extractor);
        $this->faker->expects($this->once())->method('addProvider')->with($base);
        $element->setFaker($this->faker);
        $element->addProvider($base);
    }


    public function testSeedCallsFakerSeed(): void
    {
        $this->faker->expects($this->once())->method('seed')->with(12);
        $element = new ContaoFakerElement($this->extractor);
        $element->setFaker($this->faker);
        $element->seed(12);
    }
}
