<?php
declare(strict_types=1);

namespace App\Common\UI\Validation\DTO\Error;

/**
 * DTO: This type of object is only responsible to carry data without behaviour (except being serializable)
 *
 * Following RFC-7807
 * @see https://tools.ietf.org/html/rfc7807
 * @author Guillaume MOREL <me@gmorel.io>
 */
class FormErrorItemRfc7807DTO implements \JsonSerializable
{
    public string $propertyPath;
    public string $message;

    /**
     * @inheritdoc
     */
    public function jsonSerialize()
    {
        return [
            'propertyPath' => $this->propertyPath,
            'message' => $this->message,
        ];
    }
}
