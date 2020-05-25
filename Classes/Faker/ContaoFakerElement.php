<?php declare(strict_types = 1);
/**
 * @package     fakertoolbox
 * @filesource  ContaoFakerElement.php
 * @version     1.0.0
 * @since       24.05.20 - 11:28
 * @author      Patrick Froch <info@easySolutionsIT.de>
 * @see        http://easySolutionsIT.de
 * @copyright   e@sy Solutions IT 2020
 * @license     EULA
 */
namespace Esit\Fakertoolbox\Classes\Faker;

use Esit\Fakertoolbox\Classes\Exception\LocalStringIsEmptyException;
use Faker\Factory;
use Faker\Generator;

/**
 * Class ContaoFakerElement
 * @package Esit\Fakertoolbox\Classes\Faker
 */
class ContaoFakerElement
{


    /**
     * @var DcaExtractor
     */
    protected $dca;


    /**
     * @var Generator
     */
    protected $faker;


    /**
     * ContaoFaker constructor.
     * @param DcaExtractor $dca
     * @param string       $local
     */
    public function __construct(DcaExtractor $dca, string $local = 'de_DE')
    {
        if (empty($local)) {
            throw new LocalStringIsEmptyException('local string have not to be empty');
        }

        $this->dca = $dca;
        $this->setFaker(Factory::create($local));
    }


    /**
     * Setzt den Generator
     * @param Generator $faker
     */
    public function setFaker(Generator $faker): void
    {
        $this->faker = $faker;
    }


    /**
     * Gibt einen Wert für das übergebene Feld zurück.
     * @param  string $fieldname
     * @return mixed
     */
    public function get(string $fieldname)
    {
        $method     = $this->dca->getFakerMethod($fieldname);
        $parameters = $this->dca->getFakerArguments($fieldname);
        $optional   = $this->dca->getFakerOptional($fieldname);
        $faker      = $this->faker;

        if (!empty($optional)) {
            $faker = \call_user_func_array([$faker, 'optional'], $optional);
        }

        return \call_user_func_array([$faker, $method], $parameters);
    }
}