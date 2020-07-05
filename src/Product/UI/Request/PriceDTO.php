<?php
declare(strict_types=1);

namespace App\Product\UI\Request;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * /!\ Properties are public:
 * @see https://jimmybogard.com/immutability-in-dtos/
 * I let you decide whether you prefer Immutable DTO or not.
 *
 * @author Guillaume MOREL <me@gmorel.io>
 */
final class PriceDTO
{
    /**
     * @Assert\Positive
     */
    public int $amount;

    /**
     * @Assert\Currency
     */
    public string $currency;
}
