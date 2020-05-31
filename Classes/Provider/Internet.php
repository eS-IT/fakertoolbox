<?php declare(strict_types = 1);
/**
 * @package     fakertoolbox
 * @filesource  Internet.php
 * @version     1.0.0
 * @since       30.05.20 - 17:55
 * @author      Patrick Froch <info@easySolutionsIT.de>
 * @see        http://easySolutionsIT.de
 * @copyright   e@sy Solutions IT 2020
 * @license     EULA
 */
namespace Esit\Fakertoolbox\Classes\Provider;

use Esit\Fakertoolbox\Classes\Exception\ArrayIsEmptyException;
use Faker\Provider\Base;

/**
 * Class Internet
 * @package Esit\Fakertoolbox\Classes\Provider
 */
class Internet extends Base
{


    /**
     * Gibt eine Internetadresse mit dem folgenden Schema zurück: http(s)://www.example.org/
     * @param  array|string[] $protocols
     * @return string
     */
    public function internetAddress(array $protocols = ['https://', 'http://']): string
    {
        if (0 === \count($protocols)) {
            throw new ArrayIsEmptyException('protocols have not to be empty');
        }

        $scheme = $this->generator->randomElement($protocols);
        $www    = $this->generator->optional(0.8, '')->randomElement(['www.']); // 80% chance of get 'www.'
        $domain = $this->generator->domainName;

        return $scheme . $www . $domain . '/';
    }
}
