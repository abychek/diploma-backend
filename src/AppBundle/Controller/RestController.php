<?php

namespace AppBundle\Controller;


use AppBundle\Repository\ResourceRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

abstract class RestController extends AbstractController
{
    /**
     * @param Request $request
     * @return array
     */
    protected function handleOptions(Request $request)
    {
        return array_merge(
            parent::handleOptions($request),
            $this->getFiltration($request),
            $this->getSort($request)
        );
    }

    /**
     * @param Request $request
     * @return array
     */
    private function getFiltration(Request $request)
    {
        $query = [];
        foreach ($this->getLikeFiltrationFields() as $field) {
            $query[$field] = '%' . $request->get($field, '') . '%';
        }

        foreach ($this->getInFiltrationFields() as $field) {
            if ($request->query->has($field)) {
                $query[$field] = explode(',', $request->get($field));
            }
        }

        return $query;
    }

    private function getSort(Request $request)
    {
        $result = [];
        if ($request->query->has(ResourceRepository::OPTION_SORT)) {
            $sort = $request->query->get(ResourceRepository::OPTION_SORT);
            $sort = explode(':', $sort);
            if (!in_array($sort[0], $this->getAllowedToSort())) {
                $sort[0] = $this->getDefaultSort();
            }
            if (!isset($sort[1]) || !in_array($sort[1], ['ASC', 'DESC'])) {
                $sort[1] = 'ASC';
            }
            $sort = [$sort[0] => $sort[1]];
            $result = [
                ResourceRepository::OPTION_SORT => $sort
            ];
        }
        return $result;
    }

    /**
     * @return array
     */
    abstract protected function getAllowedToSort();

    /**
     * @return string
     */
    abstract protected function getDefaultSort();

    /**
     * @return array
     */
    abstract protected function getLikeFiltrationFields();

    /**
     * @return array
     */
    abstract protected function getInFiltrationFields();

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