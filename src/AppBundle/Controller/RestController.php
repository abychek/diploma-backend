<?php

namespace AppBundle\Controller;


use AppBundle\Repository\ResourceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

abstract class RestController extends Controller
{
    protected function handlePagination(Request $request)
    {
        return [
            ResourceRepository::OPTION_FROM => $request->get('from') ? : 0,
            ResourceRepository::OPTION_SIZE => $request->get('size') ? : 100
        ];
    }

    protected function notFoundResponse()
    {
        return new JsonResponse(['message' => 'Not found'], JsonResponse::HTTP_NOT_FOUND);
    }

    protected function invalidArgumentResponse()
    {
        return new JsonResponse(['message' => 'Invalid arguments'], JsonResponse::HTTP_BAD_REQUEST);
    }
}