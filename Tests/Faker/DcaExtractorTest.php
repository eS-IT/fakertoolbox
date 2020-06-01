<?php declare(strict_types=1);
/**
 * @author      pfroch <info@easySolutionsIT.de>
 * @link        http://easySolutionsIT.de
 * @copyright   e@sy Solutions IT 2020
 * @license     EULA
 * @package     fakertoolbox
 * @filesource  DcaExtractorTest.php
 * @version     1.0.0
 * @since       24.05.20 - 12:52
 */
namespace Esit\Fakertoolbox\Tests\Faker;

use Esit\Fakertoolbox\Classes\Exception\DcaNotFoundException;
use Esit\Fakertoolbox\Classes\Exception\FakerMethodNotFound;
use Esit\Fakertoolbox\Classes\Exception\TableIsEmptyException;
use Esit\Fakertoolbox\Classes\Faker\DcaExtractor;
use PHPUnit\Framework\TestCase;

/**
 * Class DcaExtractorTest
 * @package Esit\Fakertoolbox\Tests\Faker
 */
class DcaExtractorTest extends TestCase
{


    protected function setUp(): void
    {
        if (isset($GLOBALS['TL_DCA']['tl_table'])) {
            unset($GLOBALS['TL_DCA']['tl_table']);
        }
    }


    public function testConstructorThrowsExceptionIfTabelIsEmpty(): void
    {
        $this->expectException(TableIsEmptyException::class);
        new DcaExtractor('');
    }


    public function testConstructorThrowsExceptionIfDcaForTableIsNotFound(): void
    {
        $this->expectException(DcaNotFoundException::class);
        new DcaExtractor('tl_table');
    }


    public function testGetFakerMethodThrowExceptionIfNoFakerMethodeDefined(): void
    {
        $GLOBALS['TL_DCA']['tl_table']['fields']['id']['eval'] = ['tl_class'=>'w50'];
        $extractor = new DcaExtractor('tl_table');
        $this->expectException(FakerMethodNotFound::class);
        $extractor->getFakerMethod('id');
    }


    public function testGetFakerMethodReturnMethodName(): void
    {
        $GLOBALS['TL_DCA']['tl_table']['fields']['id']['eval'] = ['tl_class'=>'w50', 'fakerMethod'=>'firstname'];
        $extractor  = new DcaExtractor('tl_table');
        $rtn        = $extractor->getFakerMethod('id');
        $this->assertSame('firstname', $rtn);
    }


    public function testGetFakerArgumentsDoNothingIfNoArgumentsFound(): void
    {
        $GLOBALS['TL_DCA']['tl_table']['fields']['id']['eval'] = ['tl_class'=>'w50', 'fakerMethod'=>'firstname'];
        $extractor  = new DcaExtractor('tl_table');
        $rtn        = $extractor->getFakerArguments('id');
        $this->assertEmpty($rtn);
    }


    public function testGetFakerArgumentsReturnArgumentsIfFound(): void
    {
        $GLOBALS['TL_DCA']['tl_table']['fields']['id']['eval'] = [
            'tl_class'          => 'w50',
            'fakerMethod'       => 'firstname',
            'fakerParameter'    => ['test', 12]
        ];
        $extractor  = new DcaExtractor('tl_table');
        $rtn        = $extractor->getFakerArguments('id');
        $this->assertSame(['test', 12], $rtn);
    }

    public function testGetFakerOptionalDoNothingIfOptionalIsNotFound(): void
    {
        $GLOBALS['TL_DCA']['tl_table']['fields']['id']['eval'] = ['tl_class'=>'w50', 'fakerMethod'=>'firstname'];
        $extractor  = new DcaExtractor('tl_table');
        $rtn        = $extractor->getFakerOptional('id');
        $this->assertEmpty($rtn);
    }


    public function testGetFakerOptionalReturnOptionalIfFound(): void
    {
        $GLOBALS['TL_DCA']['tl_table']['fields']['id']['eval'] = [
            'tl_class'      => 'w50',
            'fakerMethod'   => 'firstname',
            'fakerOptional' => ['test', 12]
        ];
        $extractor  = new DcaExtractor('tl_table');
        $rtn        = $extractor->getFakerOptional('id');
        $this->assertSame(['test', 12], $rtn);
    }


    public function testGetFakerFieldsReturnEmptyArrayIfNoFieldsForFakeFound(): void
    {
        $GLOBALS['TL_DCA']['tl_table']['fields'] = [
            'id'        => ['eval' => ['fakerMethod_NOT_FOUND'=>'firstname']],
            'name'      => ['eval' => ['fakerMethod_NOT_FOUND'=>'firstname']],
            'noMethode' => ['eval' => ['fakerMethod_NOT_FOUND'=>'firstname']],
        ];

        $extractor  = new DcaExtractor('tl_table');
        $rtn        = $extractor->getFakerFields();
        $this->assertEmpty($rtn);
    }


    public function testGetFakerFieldsReturnArrayWithFieldnamesIfFieldsForFakeFound(): void
    {
        $GLOBALS['TL_DCA']['tl_table']['fields'] = [
            'id'        => ['eval' => ['fakerMethod'=>'firstname']],
            'name'      => ['eval' => ['fakerMethod'=>'firstname']],
            'noMethode' => ['eval' => ['fakerMethod_NOT_FOUND'=>'firstname']],
        ];

        $extractor  = new DcaExtractor('tl_table');
        $rtn        = $extractor->getFakerFields();
        $this->assertSame(['id', 'name'], $rtn);
    }


    public function testGetFakerUniqueReturnFalseIfNotFound(): void
    {
        $GLOBALS['TL_DCA']['tl_table']['fields']['id']['eval'] = [
            'tl_class'      => 'w50',
            'fakerMethod'   => 'firstname'
        ];
        $extractor  = new DcaExtractor('tl_table');
        $rtn        = $extractor->getFakerUnique('id');
        $this->assertFalse($rtn);
    }


    public function testGetFakerUniqueReturnFalseIfItIsFalse(): void
    {
        $GLOBALS['TL_DCA']['tl_table']['fields']['id']['eval'] = [
            'tl_class'      => 'w50',
            'fakerMethod'   => 'firstname',
            'fakerUnique'   => false
        ];
        $extractor  = new DcaExtractor('tl_table');
        $rtn        = $extractor->getFakerUnique('id');
        $this->assertFalse($rtn);
    }


    public function testGetFakerUniqueReturnTrueIfItIsTrue(): void
    {
        $GLOBALS['TL_DCA']['tl_table']['fields']['id']['eval'] = [
            'tl_class'      => 'w50',
            'fakerMethod'   => 'firstname',
            'fakerUnique'   => true
        ];
        $extractor  = new DcaExtractor('tl_table');
        $rtn        = $extractor->getFakerUnique('id');
        $this->assertTrue($rtn);
    }

    public function testGetFakerSerialDoNothingIfSerialIsNotFound(): void
    {
        $GLOBALS['TL_DCA']['tl_table']['fields']['id']['eval'] = ['tl_class'=>'w50', 'fakerMethod'=>'firstname'];
        $extractor  = new DcaExtractor('tl_table');
        $rtn        = $extractor->getFakerSerial('id');
        $this->assertEmpty($rtn);
    }


    public function testGetFakerSerialReturnArrayIfFound(): void
    {
        $GLOBALS['TL_DCA']['tl_table']['fields']['id']['eval'] = [
            'tl_class'      => 'w50',
            'fakerMethod'   => 'firstname',
            'fakerSerial'   => [1, 5]
        ];
        $extractor  = new DcaExtractor('tl_table');
        $rtn        = $extractor->getFakerSerial('id');
        $this->assertSame([1,5], $rtn);
    }
}
