<?php declare(strict_types = 1);
/**
 * @package     fakertoolbox
 * @filesource  ContaoFaker.php
 * @version     1.0.0
 * @since       23.05.20 - 20:36
 * @author      Patrick Froch <info@easySolutionsIT.de>
 * @see        http://easySolutionsIT.de
 * @copyright   e@sy Solutions IT 2020
 * @license     EULA
 */
namespace Esit\Fakertoolbox\Classes\Faker;

/**
 * Class ContaoFaker
 * Fassade für ContaoFaker Klassen
 * @package Esit\Fakertoolbox\Classes\Faker
 */
class ContaoFaker
{


    /**
     * @var ContaoFakerElement
     */
    protected $elemet;


    /**
     * @var ContaoFakerCollection
     */
    protected $collection;


    /**
     * ContaoFaker constructor.
     * @param ContaoFakerElement    $elemet
     * @param ContaoFakerCollection $collection
     */
    public function __construct(ContaoFakerElement $elemet, ContaoFakerCollection $collection)
    {
        $this->elemet       = $elemet;
        $this->collection   = $collection;
    }


    /**
     * Fügt Faker einen eigenen Provider hinzu.
     * @param string $provider
     */
    public function addProvider(string $provider): void
    {
        $this->elemet->addProvider($provider);
    }


    /**
     * Setzt den Seed für den Generator.
     * @param int $seed
     */
    public function seed(int $seed): void
    {
        $this->elemet->seed($seed);
    }


    /**
     * Gibt einen Wert für das übergebene Feld zurück.
     * @param  string $fieldname
     * @return mixed
     */
    public function __get(string $fieldname)
    {
        return $this->elemet->get($fieldname);
    }


    /**
     * Gibt eine Tabellenzeile mit Fakewerten zurück.
     * @return array
     */
    public function getRow(): array
    {
        $rows = $this->collection->getRows(1);  // multideminsionales Array

        return \array_shift($rows);             // erste Zeile zurückgeben
    }


    /**
     * Gibt die übergebenen Anzahl an Tabellenzeilen mit Fakewerten zurück.
     * @param  int   $i
     * @return array
     */
    public function getRows(int $i): array
    {
        return $this->collection->getRows($i);
    }
}
