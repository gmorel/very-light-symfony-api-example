<?php
declare(strict_types=1);


namespace App\Product\UI\Request;

use App\Product\Application\Command\CreateProductCommand;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * Raw HTTP User Intention
 * @author Guillaume MOREL <me@gmorel.io>
 */
final class CreateProductRequest
{
    /**
     * @Assert\NotNull
     * @Assert\Uuid
     */
    private string $id;

    /**
     * @Assert\NotNull
     * @Assert\NotBlank()
     */
    private string $name;

    public function __construct(string $id, string $name)
    {
        $this->id = $id;
        $this->name = $name;
    }

    public function toCommand(): CreateProductCommand
    {
        return new CreateProductCommand(
            $this->id,
            $this->name
        );
    }
}
