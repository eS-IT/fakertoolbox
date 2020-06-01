<?php declare(strict_types = 1);
/**
 * @package     fakertoolbox
 * @filesource  DcaExtractor.php
 * @version     1.0.0
 * @since       24.05.20 - 10:46
 * @author      Patrick Froch <info@easySolutionsIT.de>
 * @see        http://easySolutionsIT.de
 * @copyright   e@sy Solutions IT 2020
 * @license     EULA
 */
namespace Esit\Fakertoolbox\Classes\Faker;

use Esit\Fakertoolbox\Classes\Exception\DcaNotFoundException;
use Esit\Fakertoolbox\Classes\Exception\FakerMethodNotFound;
use Esit\Fakertoolbox\Classes\Exception\TableIsEmptyException;

/**
 * Class DcaExtractor
 * @package Esit\Fakertoolbox\Classes\Faker
 */
class DcaExtractor
{


    /**
     * Name der Tabelle deren Daten Faker erstellen soll.
     * @var string
     */
    protected $table = '';


    /**
     * Dca der Tabelle.
     * @var array
     */
    protected $dca = [];


    /**
     * DcaExtractor constructor.
     * @param $table
     */
    public function __construct(string $table)
    {
        if (empty($table)) {
            throw new TableIsEmptyException('table have not to be empty');
        }

        if (empty($GLOBALS['TL_DCA'][$table]['fields'])) {
            throw new DcaNotFoundException("Dca for table $table has to be loaded");
        }

        $this->table    = $table;
        $this->dca      = $GLOBALS['TL_DCA'][$table]['fields'];
    }


    /**
     * Gibt die für das übergebene Feld aufzurufende Methode zurück.
     * @param $fieldname
     * @return string
     */
    public function getFakerMethod($fieldname): string
    {
        if (empty($this->dca[$fieldname]['eval']['fakerMethod'])) {
            throw new FakerMethodNotFound('fakerMethod in eval has to be set');
        }

        return $this->dca[$fieldname]['eval']['fakerMethod'];
    }


    /**
     * Gibt die für das übergebene Feld zu verwendene Parameter zurück.
     * @param $fieldname
     * @return array
     */
    public function getFakerArguments($fieldname): array
    {
        $param = [];

        if (!empty($this->dca[$fieldname]['eval']['fakerParameter'])) {
            $param = $this->dca[$fieldname]['eval']['fakerParameter'];
        }

        return $param;
    }


    /**
     * Gibt die für das übergebene Feld zu verwendene Parameter zurück.
     * @param $fieldname
     * @return array
     */
    public function getFakerOptional($fieldname): array
    {
        $optional = [];

        if (!empty($this->dca[$fieldname]['eval']['fakerOptional'])) {
            $optional = $this->dca[$fieldname]['eval']['fakerOptional'];
        }

        return $optional;
    }


    /**
     * Gibt die für das übergebene Feld zu verwendene Parameter zurück.
     * @param $fieldname
     * @return array
     */
    public function getFakerSerial($fieldname): array
    {
        $serial = [];

        if (!empty($this->dca[$fieldname]['eval']['fakerSerial'])) {
            $serial = $this->dca[$fieldname]['eval']['fakerSerial'];
        }

        return $serial;
    }


    /**
     * Gibt die für das übergebene Feld zu verwendene Parameter zurück.
     * @param $fieldname
     * @return bool
     */
    public function getFakerUnique($fieldname): bool
    {
        if (isset($this->dca[$fieldname]['eval']['fakerUnique'])) {
            return $this->dca[$fieldname]['eval']['fakerUnique'] ? true : false;
        }

        return false;
    }


    /**
     * Gibt alle Felder zurück, bei denen eine Fakermethode defineirt ist.
     * @return array
     */
    public function getFakerFields(): array
    {
        $fields = [];

        foreach ($this->dca as $fieldName => $fieldConfig) {
            if (!empty($fieldConfig['eval']['fakerMethod'])) {
                $fields[] = $fieldName;
            }
        }

        return $fields;
    }
}
