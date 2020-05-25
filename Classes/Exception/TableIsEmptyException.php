<?php declare(strict_types = 1);
/**
 * @package     fakertoolbox
 * @filesource  TableIsEmptyException.php
 * @version     1.0.0
 * @since       25.05.20 - 09:06
 * @author      Patrick Froch <info@easySolutionsIT.de>
 * @see        http://easySolutionsIT.de
 * @copyright   e@sy Solutions IT 2020
 * @license     EULA
 */
namespace Esit\Fakertoolbox\Classes\Exception;

/**
 * Class TableIsEmptyException
 * @package Esit\Fakertoolbox\Classes\Exception
 */
class TableIsEmptyException extends \InvalidArgumentException implements Exception
{
}
