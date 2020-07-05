<?php
declare(strict_types=1);


namespace App\Product\Application\Command;


use Money\Money;

/**
 * Refined User Intention
 * @author Guillaume MOREL <me@gmorel.io>
 */
final class CreateProductCommand
{
    private string $id;
    private string $name;
    private Money $price;

    public function __construct(string $id, string $name, Money $price)
    {
        $this->id = $id;
        $this->name = $name;
        $this->price = $price;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPrice(): Money
    {
        return $this->price;
    }
}
