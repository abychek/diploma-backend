<?php

namespace StaffBundle\Controller;


use AppBundle\Controller\RestController;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use StaffBundle\Entity\Position;
use StaffBundle\Repository\PositionRepository;
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
     * @ApiDoc(
     *  section="Positions",
     *  resource=true,
     *  description="Return list of positions.",
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
     *          "name"="name",
     *          "dataType"="string",
     *          "description"="Filtrate employees by Name."
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
        $positions = $this->getDoctrine()->getRepository('StaffBundle:Position')->getSortedByName($options);
        foreach ($positions as $position) {
            $result[] = $position->toArray();
        }

        return new JsonResponse($result);
    }

    /**
     * @ApiDoc(
     *  section="Positions",
     *  resource=true,
     *  description="Return concrete position.",
     *  parameters={
     *      {"name"="id", "dataType"="integer", "required"=true, "description"="Position id"}
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
        if ($position = $this->getDoctrine()->getRepository('StaffBundle:Position')->find($id)) {
            return new JsonResponse($position->toArray());
        }

        return $this->generateInfoResponse(JsonResponse::HTTP_NOT_FOUND);
    }

    /**
     * @ApiDoc(
     *  section="Positions",
     *  resource=true,
     *  description="Create new Position.",
     *  requirements={
     *      {
     *          "name"="name",
     *          "dataType"="string",
     *          "description"="Position name."
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
     * @ApiDoc(
     *  section="Positions",
     *  resource=true,
     *  description="Update position.",
     *  parameters={
     *      {"name"="id", "dataType"="integer", "required"=true, "description"="Position id"},
     *      {
     *          "name"="name",
     *          "dataType"="string",
     *          "required"=false,
     *          "description"="Position name."
     *      },
     *      {
     *          "name"="status",
     *          "dataType"="string",
     *          "required"=false,
     *          "pattern"="(available|unavailable)",
     *          "description"="Position status."
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
     * @ApiDoc(
     *  section="Positions",
     *  resource=true,
     *  description="Remove concrete position.",
     *  parameters={
     *      {"name"="id", "dataType"="integer", "required"=true, "description"="Position id"}
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
        if ($position = $this->getDoctrine()->getRepository('StaffBundle:Position')->find($id)) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($position);
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
        return [PositionRepository::FIELD_NAME];
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
            PositionRepository::FIELD_NAME
        ];
    }

    /**
     * @return string
     */
    protected function getDefaultSort()
    {
        return PositionRepository::FIELD_NAME;
    }
}