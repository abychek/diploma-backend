<?php

namespace AppBundle\Controller;


use AppBundle\Repository\ResourceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

abstract class RestController extends Controller
{
    /**
     * @param Request $request
     * @return array
     */
    protected function handlePagination(Request $request)
    {
        return [
            ResourceRepository::OPTION_FROM => $request->get('from') ? : 0,
            ResourceRepository::OPTION_SIZE => $request->get('size') ? : 100
        ];
    }

    /**
     * @param int $code
     * @return JsonResponse
     */
    protected function generateInfoResponse($code)
    {
        switch ($code) {
            case JsonResponse::HTTP_CREATED:
                $message = 'Created.';
                break;
            case JsonResponse::HTTP_NOT_FOUND:
                $message = 'Not found.';
                break;
            case JsonResponse::HTTP_BAD_REQUEST:
                $message = 'Invalid argument.';
                break;
            case JsonResponse::HTTP_NO_CONTENT:
                $message = 'Deleted.';
                break;
            case JsonResponse::HTTP_OK:
                $message = 'Updated.';
                break;
            default:
                $message = 'Internal server error.';
                break;
        }

        return new JsonResponse(['message' => $message], $code);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    abstract public function listAction(Request $request);

    /**
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    abstract public function getAction(Request $request, $id);

    /**
     * @param Request $request
     * @return JsonResponse
     */
    abstract public function createAction(Request $request);


    /**
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    abstract public function updateAction(Request $request, $id);

    /**
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    abstract public function deleteAction(Request $request, $id);
}