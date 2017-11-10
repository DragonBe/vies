<?php

declare (strict_types=1);

/**
 * Vies
 *
 * Component using the European Commission (EC) VAT Information Exchange System (VIES) to verify and validate VAT
 * registration numbers in the EU, using PHP and Composer.
 *
 * @author  Michelangelo van Dam <dragonbe+github@gmail.com>
 * @license  MIT
 *
 */
namespace DragonBe\Vies;

/**
 * ViesServiceException
 *
 * This class provides an exception layer for usage of the VIES web service
 * provided by the European commission to validate VAT numbers of companies
 * registered within the European Union
 *
 * @see \Exception
 * @package \DragonBe\Vies
 */
class ViesServiceException extends \Exception
{

}
