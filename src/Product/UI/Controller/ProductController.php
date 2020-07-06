<?php

namespace App\Product\UI\Controller;

use App\Common\UI\Validation\DTO\Error\FormErrorItemRfc7807DTO;
use App\Common\UI\Validation\DTO\Error\FormErrorRfc7807DTO;
use App\Product\Application\ProductCommandService;
use App\Product\UI\Request\CreateProductRequestPayload;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\MissingConstructorArgumentsException;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("v1/{_locale}/products",
 *     defaults={"_locale"="en"},
 *     requirements={"_locale"="en|fr"}
 * )
 */
class ProductController extends AbstractController
{
    private SerializerInterface $serializer;
    private ValidatorInterface $validator;
    private ProductCommandService $commandService;

    public function __construct(SerializerInterface $serializer, ValidatorInterface $validator, ProductCommandService $commandService)
    {
        $this->serializer = $serializer;
        $this->validator = $validator;
        $this->commandService = $commandService;
    }

    /**
     * @Route(".json", name="product_new", methods={"POST"})
     */
    public function new(Request $request): JsonResponse
    {
        try {
            /** @var CreateProductRequestPayload $requestPayload */
            $requestPayload = $this->serializer->deserialize(
                $request->getContent(),
                CreateProductRequestPayload::class,
                'json',
            );

            $errors = $this->validator->validate($requestPayload);
            if ($errors->count() > 0) {
                return new JsonResponse(
                    $this->createErrorFromValidation($errors),
                    Response::HTTP_BAD_REQUEST
                );
            }

            $this->commandService->create(
                $requestPayload->toCommand()
            );
        } catch (MissingConstructorArgumentsException $e) {
            return new JsonResponse(
                $this->createErrorFromSerialization($e),
                Response::HTTP_BAD_REQUEST
            );
        }

        return new JsonResponse(
            null,
            Response::HTTP_CREATED,
            [
                'Location' => sprintf(
                    '/v1/products/%s.json',
                    $requestPayload->id
                )
            ]
        );
    }

    private function createErrorFromSerialization(MissingConstructorArgumentsException $exception)
    {
        $mainDto = new FormErrorRfc7807DTO();
        $mainDto->title = 'Bad Request';
        $mainDto->type = 'Missing JSON node';

        // Right now Serializer/MissingConstructorArgumentsException is not exploitable without giving too much implementation (namespace) details

        return $mainDto;
    }

    private function createErrorFromValidation(ConstraintViolationListInterface $violations)
    {
        $mainDto = new FormErrorRfc7807DTO();
        $mainDto->title = 'Bad Request';
        $mainDto->type = 'UI Validation';

        $items = [];
        foreach ($violations as $item) {
            $dto = new FormErrorItemRfc7807DTO();
            $dto->propertyPath = $item->getPropertyPath();
            $dto->message = $item->getMessage();
            $items[] = $dto;
        }

        $mainDto->violations = $items;

        return $mainDto;
    }
}
