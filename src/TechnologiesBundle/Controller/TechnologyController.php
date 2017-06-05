<?php

namespace TechnologiesBundle\Controller;

use AppBundle\Controller\RestController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use TechnologiesBundle\Entity\Technology;
use TechnologiesBundle\Repository\TechnologyRepository;


/**
 * Class TechnologyController
 * @package TechnologiesBundle\Controller
 * @Route("/technologies")
 */
class TechnologyController extends RestController
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
        $options = $this->handleOptions($request);
        $technologies = $this->getDoctrine()->getRepository('TechnologiesBundle:Technology')->getSortedByTitle($options);
        foreach ($technologies as $technology) {
            $result[] = $technology->toArray();
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
        if ($technology = $this->getDoctrine()->getRepository('TechnologiesBundle:Technology')->find($id)) {
            return new JsonResponse($technology->toArray());
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
        if ($title = $request->get('name')) {
            $em = $this->getDoctrine()->getManager();
            $em->persist((new Technology())
                ->setTitle($title)
                ->setStatus(Technology::STATUS_AVAILABLE));
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
        if ($technology = $this->getDoctrine()->getRepository('TechnologiesBundle:Technology')->find($id)) {
            $title = $request->get('title', $technology->getTitle());
            $status = $request->get('status', $technology->getStatus());
            if ($title || $status) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($technology->setTitle($title)->setStatus($status));
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
        if ($technology = $this->getDoctrine()->getRepository('TechnologiesBundle:Technology')->find($id)) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($technology);
            $em->flush();

            return $this->generateInfoResponse(JsonResponse::HTTP_NO_CONTENT);
        }

        return $this->generateInfoResponse(JsonResponse::HTTP_NOT_FOUND);
    }

    /**
     * @return array
     */
    protected function getLikeFiltrationFields()
    {
        return [TechnologyRepository::FIELD_TITLE];
    }

    /**
     * @return array
     */
    protected function getInFiltrationFields()
    {
        return [];
    }
}
