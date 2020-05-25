<?php declare(strict_types = 1);
/**
 * @package     fakertoolbox
 * @filesource  ContaoFakerCollection.php
 * @version     1.0.0
 * @since       24.05.20 - 11:30
 * @author      Patrick Froch <info@easySolutionsIT.de>
 * @see        http://easySolutionsIT.de
 * @copyright   e@sy Solutions IT 2020
 * @license     EULA
 */
namespace Esit\Fakertoolbox\Classes\Faker;

/**
 * Class ContaoFakerCollection
 * @package Esit\Fakertoolbox\Classes\Faker
 */
class ContaoFakerCollection
{


    /**
     * @var ContaoFakerElement
     */
    protected $element;


    /**
     * @var DcaExtractor
     */
    protected $extractor;


    /**
     * ContaoFakerCollection constructor.
     * @param ContaoFakerElement $element
     * @param DcaExtractor       $extractor
     */
    public function __construct(ContaoFakerElement $element, DcaExtractor $extractor)
    {
        $this->element      = $element;
        $this->extractor    = $extractor;
    }


    /**
     * Gibt die übergebenen Anzahl an Tabellenzeilen mit Fakewerten zurück.
     * @param  int           $count
     * @return array|array[]
     */
    public function getRows(int $count): array
    {
        $data   = [];
        $fields = $this->extractor->getFakerFields();

        for ($i=0; $i < $count; $i++) {
            foreach ($fields as $fieldName) {
                $data[$i][$fieldName] = $this->element->get($fieldName);
            }
        }

        return \count($data) ? $data : [[]];
    }
}
