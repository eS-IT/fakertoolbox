<?php declare(strict_types=1);
/**
 * @author      pfroch <info@easySolutionsIT.de>
 * @link        http://easySolutionsIT.de
 * @copyright   e@sy Solutions IT 2020
 * @license     EULA
 * @package     fakertoolbox
 * @filesource  ContaoFakerCollectionTest.php
 * @version     1.0.0
 * @since       24.05.20 - 12:51
 */
namespace Esit\Fakertoolbox\Tests\Faker;

use Esit\Fakertoolbox\Classes\Faker\ContaoFakerCollection;
use Esit\Fakertoolbox\Classes\Faker\ContaoFakerElement;
use Esit\Fakertoolbox\Classes\Faker\DcaExtractor;
use PHPUnit\Framework\TestCase;

/**
 * Class ContaoFakerCollectionTest
 * @package Esit\Fakertoolbox\Tests\Faker
 */
class ContaoFakerCollectionTest extends TestCase
{


    /**
     * @var ContaoFakerElement
     */
    protected $element;


    /**
     * @var DcaExtractor
     */
    protected $extractor;


    protected $collection;


    protected function setUp(): void
    {
        $this->element  = $this->getMockBuilder(ContaoFakerElement::class)
                               ->disableOriginalConstructor()
                               ->onlyMethods(['get'])
                               ->getMock();

        $this->extractor = $this->getMockBuilder(DcaExtractor::class)
                                ->disableOriginalConstructor()
                                ->onlyMethods(['getFakerFields'])
                                ->getMock();

        $this->collection= new ContaoFakerCollection($this->element, $this->extractor);
    }


    public function testGetRowsDoNothingIfCountIsZero(): void
    {
        $this->extractor->expects($this->once())->method('getFakerFields');
        $this->element->expects($this->never())->method('get');
        $rowCount   = 0;
        $this->collection->getRows($rowCount);
    }


    public function testGetRowsDoNothingIfCountIsUnderZero(): void
    {
        $this->extractor->expects($this->once())->method('getFakerFields');
        $this->element->expects($this->never())->method('get');
        $rowCount   = -12;
        $this->collection->getRows($rowCount);
    }


    public function testGetRowsDoNothingIfNoFieldsFound(): void
    {
        $this->extractor->expects($this->once())->method('getFakerFields')->willReturn([]);
        $this->element->expects($this->never())->method('get');
        $rowCount   = 1;
        $this->collection->getRows($rowCount);
    }


    public function testGetRowsCallElementForEveryFieldOneTimes(): void
    {
        $expected   = [['id'=>'test', 'name'=>'test']];
        $rowCount   = 1;
        $fields     = ['id','name'];
        $this->extractor->expects($this->once())->method('getFakerFields')->willReturn($fields);
        $this->element->expects($this->exactly(\count($fields)))->method('get')->willReturn('test');
        $rows = $this->collection->getRows($rowCount);
        $this->assertSame($expected, $rows);
    }


    public function testGetRowsCallElementForEveryFieldInEveryRow(): void
    {
        $expected   = [['id'=>'test', 'name'=>'test'], ['id'=>'test', 'name'=>'test'], ['id'=>'test', 'name'=>'test']];
        $rowCount   = 3;
        $fields     = ['id','name'];
        $this->extractor->expects($this->once())->method('getFakerFields')->willReturn($fields);
        $this->element->expects($this->exactly($rowCount * \count($fields)))->method('get')->willReturn('test');
        $rows = $this->collection->getRows($rowCount);
        $this->assertSame($expected, $rows);
    }
}
