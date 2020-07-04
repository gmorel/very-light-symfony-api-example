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
class FormErrorRfc7807DTO implements \JsonSerializable
{
    public string $type = 'https://example.net/validation-error';
    public string $title;
    public array $violations = [];

    /**
     * @inheritdoc
     */
    public function jsonSerialize()
    {
        $violations = [];
        foreach ($this->violations as $errorItem) {
            $violations[] = $errorItem->jsonSerialize();
        }

        return [
            'type' => $this->type,
            'title' => $this->title,
            'violations' => $violations
        ];
    }
}
