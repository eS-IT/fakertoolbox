<?php declare(strict_types=1);
/**Faker
 * @author      pfroch <info@easySolutionsIT.de>
 * @link        http://easySolutionsIT.de
 * @copyright   e@sy Solutions IT 2020
 * @license     EULA
 * @package     fakertoolbox
 * @filesource  ContaoFakerTest.php
 * @version     1.0.0
 * @since       24.05.20 - 12:50
 */
namespace Esit\Fakertoolbox\Tests\Faker;

use Esit\Fakertoolbox\Classes\Faker\ContaoFaker;
use Esit\Fakertoolbox\Classes\Faker\ContaoFakerCollection;
use Esit\Fakertoolbox\Classes\Faker\ContaoFakerElement;
use Faker\Provider\Base;
use PHPUnit\Framework\TestCase;

/**
 * Class ContaoFakerTest
 * @package Esit\Fakertoolbox\Tests\Faker
 */
class ContaoFakerTest extends TestCase
{


    /**
     * @var ContaoFakerElement
     */
    protected $element;


    /**
     * @var ContaoFakerCollection
     */
    protected $collection;


    /**
     * @var ContaoFaker
     */
    protected $conatoFaker;


    protected function setUp(): void
    {
        $this->element      = $this->getMockBuilder(ContaoFakerElement::class)
                                   ->disableOriginalConstructor()
                                   ->onlyMethods(['get', 'addProvider', 'seed'])
                                   ->getMock();
        $this->collection   = $this->getMockBuilder(ContaoFakerCollection::class)
                                   ->disableOriginalConstructor()
                                   ->onlyMethods(['getRows'])
                                   ->getMock();

        $this->conatoFaker  = new ContaoFaker($this->element, $this->collection);
    }


    public function test__get_calls_element(): void
    {
        $this->element->expects($this->once())->method('get')->with('testProperty');
        $this->conatoFaker->testProperty ;
    }


    public function testGetRowCallCollectionWithOneAndShiftTheArray(): void
    {
        $this->collection->expects($this->once())->method('getRows')->with(1)->willReturn([['test']]);
        $rtn = $this->conatoFaker->getRow();
        $this->assertSame(['test'], $rtn);
    }


    public function testGetRowsCallsCollectionWithInteger(): void
    {
        $this->collection->expects($this->once())->method('getRows')->with(5);
        $this->conatoFaker->getRows(5);
    }


    public function testAddProviderCallsElementAddPRovider(): void
    {
        $base = $this->getMockBuilder(Base::class)->disableOriginalConstructor()->getMock();
        $this->element->expects($this->once())->method('addProvider')->with($base);
        $this->conatoFaker->addProvider($base);
    }


    public function testSeedCallsElementSeed(): void
    {
        $this->element->expects($this->once())->method('seed')->with(12);
        $this->conatoFaker->seed(12);
    }
}
