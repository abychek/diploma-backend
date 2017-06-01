<?php

namespace StaffBundle\Controller;


use AppBundle\Controller\RestController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use StaffBundle\Entity\Position;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class PositionController
 * @package StaffBundle\Controller
 * @Route("/positions")
 */
class PositionController extends RestController
{
    /**
     * @Route("/")
     * @Method({"GET"})
     * @param Request $request
     * @return JsonResponse
     */
    public function listAction(Request $request)
    {
        $result = [];
        $options = $this->handlePagination($request);
        $positions = $this->getDoctrine()->getRepository('StaffBundle:Position')->getSortedByName($options);
        foreach ($positions as $position) {
            $result[] = $position->toArray();
        }

        return new JsonResponse($result);
    }

    /**
     * @Route("/{id}/")
     * @Method({"GET"})
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    public function getAction(Request $request, $id)
    {
        if ($position = $this->getDoctrine()->getRepository('StaffBundle:Position')->find($id)) {
            return new JsonResponse($position->toArray());
        }

        return $this->generateInfoResponse(JsonResponse::HTTP_NOT_FOUND);
    }

    /**
     * @Route("/")
     * @Method({"POST"})
     * @param Request $request
     * @return JsonResponse
     */
    public function createAction(Request $request)
    {
        if ($name = $request->get('name')) {
            $em = $this->getDoctrine()->getManager();
            $em->persist((new Position())
                ->setName($name)
                ->setStatus(Position::STATUS_AVAILABLE));
            $em->flush();

            return $this->generateInfoResponse(JsonResponse::HTTP_CREATED);
        }

        return $this->generateInfoResponse(JsonResponse::HTTP_BAD_REQUEST);
    }

    /**
     * @Route("/{$id}")
     * @Method({"PUT"})
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    public function updateAction(Request $request, $id)
    {
        if ($position = $this->getDoctrine()->getRepository('StaffBundle:Position')->find($id)) {
            $name = $request->get('name', $position->getName());
            $status = $request->get('status', $position->getStatus());
            if ($name || $status) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($position->setName($name)->setStatus($status));
                $em->flush();

                return $this->generateInfoResponse(JsonResponse::HTTP_OK);
            }

            return $this->generateInfoResponse(JsonResponse::HTTP_BAD_REQUEST);
        }

        return $this->generateInfoResponse(JsonResponse::HTTP_NOT_FOUND);
    }

    /**
     * @Route("/{$id}")
     * @Method({"DELETE"})
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    public function deleteAction(Request $request, $id)
    {
        if ($position = $this->getDoctrine()->getRepository('StaffBundle:Position')->find($id)) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($position);
            $em->flush();

            return $this->generateInfoResponse(JsonResponse::HTTP_NO_CONTENT);
        }

        return $this->generateInfoResponse(JsonResponse::HTTP_NOT_FOUND);
    }
}