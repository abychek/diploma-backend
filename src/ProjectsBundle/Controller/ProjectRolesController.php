<?php

namespace ProjectsBundle\Controller;


use AppBundle\Controller\RestController;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use ProjectsBundle\Entity\ProjectRole;
use ProjectsBundle\Repository\ProjectRolesRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class ProjectRolesController
 * @package ProjectsBundle\Controller
 * @Route("/project-roles")
 */
class ProjectRolesController extends RestController
{

    /**
     * @ApiDoc(
     *  section="Project roles",
     *  resource=true,
     *  description="Return list of project roles.",
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
     *          "description"="Filtrate project roles by Name."
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
        $roles = $this->getDoctrine()->getRepository('ProjectsBundle:ProjectRole')->getByRoleName($options);
        foreach ($roles as $role) {
            $result[] = $role->toArray();
        }

        return new JsonResponse($result);
    }

    /**
     * @ApiDoc(
     *  section="Project roles",
     *  resource=true,
     *  description="Return concrete project role.",
     *  parameters={
     *      {"name"="id", "dataType"="integer", "required"=true, "description"="Project role id"}
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
        if ($role = $this->getDoctrine()->getRepository('ProjectsBundle:ProjectRole')->find($id)) {
            return new JsonResponse($role->toArray());
        }

        return $this->generateInfoResponse(JsonResponse::HTTP_NOT_FOUND);
    }

    /**
     * @ApiDoc(
     *  section="Project roles",
     *  resource=true,
     *  description="Create new project role.",
     *  requirements={
     *      {
     *          "name"="name",
     *          "dataType"="string",
     *          "description"="Project role name."
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
        $name = $request->get('name');
        if ($name) {
            $em = $this->getDoctrine()->getManager();
            $em->persist((new ProjectRole())
                ->setRoleName($name)
                ->setStatus(ProjectRole::STATUS_AVAILABLE));
            $em->flush();

            return $this->generateInfoResponse(JsonResponse::HTTP_CREATED);
        }

        return $this->generateInfoResponse(JsonResponse::HTTP_BAD_REQUEST);
    }

    /**
     * @ApiDoc(
     *  section="Project roles",
     *  resource=true,
     *  description="Update project.",
     *  parameters={
     *      {"name"="id", "dataType"="integer", "required"=true, "description"="Project id"},
     *      {
     *          "name"="name",
     *          "dataType"="string",
     *          "required"=false,
     *          "description"="Project role name."
     *      },
     *      {
     *          "name"="status",
     *          "dataType"="string",
     *          "required"=false,
     *          "pattern"="(available|unavailable)",
     *          "description"="Project role status."
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
        if ($role = $this->getDoctrine()->getRepository('ProjectsBundle:ProjectRole')->find($id)) {
            $name = $request->get('name');
            if ($name) {
                $em = $this->getDoctrine()->getManager();
                $em->persist((new ProjectRole())
                    ->setRoleName($name)
                    ->setStatus(ProjectRole::STATUS_AVAILABLE));
                $em->flush();

                return $this->generateInfoResponse(JsonResponse::HTTP_CREATED);
            }

            return $this->generateInfoResponse(JsonResponse::HTTP_BAD_REQUEST);
        }

        return $this->generateInfoResponse(JsonResponse::HTTP_NOT_FOUND);
    }

    /**
     * @ApiDoc(
     *  section="Project roles",
     *  resource=true,
     *  description="Remove concrete project role.",
     *  parameters={
     *      {"name"="id", "dataType"="integer", "required"=true, "description"="Project role id"}
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
        if ($role = $this->getDoctrine()->getRepository('ProjectsBundle:ProjectRole')->find($id)) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($role);
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
        return [ProjectRolesRepository::FIELD_ROLE_NAME];
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
            ProjectRolesRepository::FIELD_ROLE_NAME
        ];
    }

    /**
     * @return string
     */
    protected function getDefaultSort()
    {
        return ProjectRolesRepository::FIELD_ROLE_NAME;
    }
}