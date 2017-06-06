<?php

namespace TechnologiesBundle\Controller;

use AppBundle\Controller\RestController;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
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
     * @ApiDoc(
     *  section="Technologies",
     *  resource=true,
     *  description="Return list of technologies.",
     *  filters={
     *      {"name"="size", "dataType"="integer", "description"="Size of returned data"},
     *      {"name"="from", "dataType"="integer", "description"="Start position of returned data"},
     *      {
     *          "name"="sort",
     *          "dataType"="string",
     *          "pattern"="field:(name); strategy:(ASC|DESC)",
     *          "description"="Sorted field and strategy ({field}:{strategy})"
     *      },
     *      {
     *          "name"="title",
     *          "dataType"="string",
     *          "description"="Filtrate technologies by Title."
     *      }
     *  }
     * )
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
     * @ApiDoc(
     *  section="Technologies",
     *  resource=true,
     *  description="Return concrete technology.",
     *  parameters={
     *      {"name"="id", "dataType"="integer", "required"=true, "description"="Technology id"}
     *  }
     * )
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
     * @ApiDoc(
     *  section="Technologies",
     *  resource=true,
     *  description="Create new Technology.",
     *  requirements={
     *      {
     *          "name"="title",
     *          "dataType"="string",
     *          "description"="Technology title."
     *      }
     *  }
     * )
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
     * @ApiDoc(
     *  section="Technologies",
     *  resource=true,
     *  description="Update technology.",
     *  parameters={
     *      {"name"="id", "dataType"="integer", "required"=true, "description"="Technology id"},
     *      {
     *          "name"="title",
     *          "dataType"="string",
     *          "required"=false,
     *          "description"="Technology title."
     *      },
     *      {
     *          "name"="status",
     *          "dataType"="string",
     *          "required"=false,
     *          "pattern"="(available|unavailable)",
     *          "description"="Technology status."
     *      }
     *  }
     * )
     * @Route("/{id}/")
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
     * @ApiDoc(
     *  section="Technologies",
     *  resource=true,
     *  description="Remove concrete technology.",
     *  parameters={
     *      {"name"="id", "dataType"="integer", "required"=true, "description"="Technology id"}
     *  }
     * )
     * @Route("/{id}/")
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

    /**
     * @return array
     */
    protected function getAllowedToSort()
    {
        return [
            TechnologyRepository::FIELD_TITLE
        ];
    }

    /**
     * @return string
     */
    protected function getDefaultSort()
    {
        return TechnologyRepository::FIELD_TITLE;
    }
}
