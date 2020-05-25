<?php declare(strict_types = 1);
/**
 * @package     fakertoolbox
 * @filesource  FakerFactory.php
 * @version     1.0.0
 * @since       23.05.20 - 20:10
 * @author      Patrick Froch <info@easySolutionsIT.de>
 * @see        http://easySolutionsIT.de
 * @copyright   e@sy Solutions IT 2020
 * @license     EULA
 */
namespace Esit\Fakertoolbox\Classes\Services\Factories;

use Esit\Fakertoolbox\Classes\Exception\LocalStringIsEmptyException;
use Esit\Fakertoolbox\Classes\Exception\TableIsEmptyException;
use Esit\Fakertoolbox\Classes\Faker\ContaoFaker;
use Esit\Fakertoolbox\Classes\Faker\ContaoFakerCollection;
use Esit\Fakertoolbox\Classes\Faker\ContaoFakerElement;
use Esit\Fakertoolbox\Classes\Faker\DcaExtractor;

/**
 * Class FakerFactory
 * @package Esit\Fakertoolbox\Classes\Services\Factories
 */
class FakerFactory
{


    /**
     * Gibt eine Instanz von ContaoFaker für eine bestimmte Tabelle zurück.
     * @param  string      $table
     * @param  string      $local
     * @return ContaoFaker
     * @throws \Exception
     */
    public function getFaker(string $table, string $local = 'de_DE'): ContaoFaker
    {
        if (empty($table)) {
            throw new TableIsEmptyException('table have not to be empty');
        }

        if (empty($local)) {
            throw new LocalStringIsEmptyException('local string have not to be empty');
        }

        $extractor  = new DcaExtractor($table);
        $element    = new ContaoFakerElement($extractor, $local);
        $collection = new ContaoFakerCollection($element, $extractor);

        return new ContaoFaker($element, $collection);
    }
}
