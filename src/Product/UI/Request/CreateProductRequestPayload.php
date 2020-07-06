<?php
declare(strict_types=1);


namespace App\Product\UI\Request;

use App\Product\Application\Command\CreateProductCommand;
use Money\Currency;
use Money\Money;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * DTO representing how API ingest JSON
 * /!\ Properties are public:
 * @see https://jimmybogard.com/immutability-in-dtos/
 * I let you decide whether you prefer Immutable DTO or not.
 *
 * @author Guillaume MOREL <me@gmorel.io>
 */
final class CreateProductRequestPayload
{
    /**
     * @Assert\NotNull
     * @Assert\Uuid
     */
    public string $id;

    /**
     * @Assert\NotNull
     * @Assert\NotBlank
     */
    public string $name;

    /**
     * @Assert\NotNull
     * @Assert\Valid
     */
    public PriceDTO $price;

    public function toCommand(): CreateProductCommand
    {
        return new CreateProductCommand(
            $this->id,
            $this->name,
            new Money( // Javascript app sends number
                $this->price->amount, // But internally Symfony app manages price amount as string via http://moneyphp.org/ library
                new Currency($this->price->currency)
            )
        );
    }
}
