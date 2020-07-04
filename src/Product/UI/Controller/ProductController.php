<?php

namespace App\Product\UI\Controller;

use App\Product\Application\ProductCommandService;
use App\Product\UI\Request\CreateProductRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\MissingConstructorArgumentsException;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("v1/products")
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
            /** @var CreateProductRequest $refinedRequest */
            $refinedRequest = $this->serializer->deserialize(
                $request->getContent(),
                CreateProductRequest::class,
                'json',
            );

            $errors = $this->validator->validate($refinedRequest);
            if ($errors->count() > 0) {
                // @todo Return 400: UI Validation
            }
            $command = $refinedRequest->toCommand();
            $this->commandService->create($command);
        } catch (MissingConstructorArgumentsException $e) {
            // @todo Return 400: Missing JSON nodes
        }

        return new JsonResponse(
            null,
            Response::HTTP_CREATED,
            [
                'Location' => sprintf('/v1/products/%s.json', $command->getId())
            ]
        );
    }
}
