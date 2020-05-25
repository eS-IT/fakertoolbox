<?php declare(strict_types=1);
/**
 * @author      pfroch <info@easySolutionsIT.de>
 * @link        http://easySolutionsIT.de
 * @copyright   e@sy Solutions IT 2020
 * @license     EULA
 * @package     fakertoolbox
 * @filesource  FakerFactoryTest.php
 * @version     1.0.0
 * @since       24.05.20 - 12:47
 */
namespace Esit\Fakertoolbox\Tests\Services\Factories;

use Esit\Fakertoolbox\Classes\Exception\LocalStringIsEmptyException;
use Esit\Fakertoolbox\Classes\Exception\TableIsEmptyException;
use Esit\Fakertoolbox\Classes\Faker\ContaoFaker;
use Esit\Fakertoolbox\Classes\Services\Factories\FakerFactory;
use PHPUnit\Framework\MockObject\Stub\ReturnStub;
use PHPUnit\Framework\TestCase;

/**
 * Class FakerFactoryTest
 * @package Esit\Fakertoolbox\Tests\Services\Factories
 */
class FakerFactoryTest extends TestCase
{


    /**
     * @throws \Exception
     */
    public function testGetFakerThrowsExceptionIfTableIsEmpty(): void
    {
        $factory = new FakerFactory();
        $this->expectException(TableIsEmptyException::class);
        $factory->getFaker('', 'de_DE');
    }


    /**
     * @throws \Exception
     */
    public function testGetFakerThrowsExceptionIfLocalStringIsEmpty(): void
    {
        $factory = new FakerFactory();
        $this->expectException(LocalStringIsEmptyException::class);
        $factory->getFaker('tl_table', '');
    }

    public function testGetFakerRetrunContaoFaker(): void
    {
        $factory    = new FakerFactory();
        $rtn        = $factory->getFaker('tl_table');
        $this->assertInstanceOf(ContaoFaker::class, $rtn);
    }
}
