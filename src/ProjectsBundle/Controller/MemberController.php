<?php

namespace ProjectsBundle\Controller;


use AppBundle\Controller\RestController;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use ProjectsBundle\Entity\Member;
use ProjectsBundle\Repository\MemberRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class MembersController
 * @package ProjectsBundle\Controller
 * @Route("/projects/{projectId}/members")
 */
class MemberController extends RestController
{

    /**
     * @ApiDoc(
     *  section="Projects",
     *  resource=true,
     *  description="Return list of project members.",
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
     *          "description"="Filtrate members by Employee name."
     *      }
     *  },
     *  parameters={
     *      {"name"="projectId", "dataType"="integer", "required"=true, "description"="Project id."}
     *  }
     * )
     * @Route("/")
     * @Method({"GET"})
     * @param Request $request
     * @return JsonResponse
     */
    public function listAction(Request $request)
    {
        if ($request->get('projectId') && $project = $this->getProjectById($request->get('projectId'))) {
            $result = [];
            $options = $this->handleOptions($request);
            $members = $this->getDoctrine()->getRepository('ProjectsBundle:Member')->getByProject($project, $options);
            foreach ($members as $member) {
                $result[] = $member->toArray();
            }

            return new JsonResponse($result);
        }

        return $this->generateInfoResponse(JsonResponse::HTTP_NOT_FOUND);
    }

    /**
     * @ApiDoc(
     *  section="Projects",
     *  resource=true,
     *  description="Return concrete project member.",
     *  parameters={
     *      {"name"="projectId", "dataType"="integer", "required"=true, "description"="Project id"},
     *      {"name"="id", "dataType"="integer", "required"=true, "description"="Member id"}
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
        if (
            $request->get('projectId') &&
            $this->getDoctrine()->getRepository('ProjectsBundle:Project')->find($request->get('projectId')) &&
            $member = $this->getDoctrine()->getRepository('ProjectsBundle:Member')->find($id)
        ) {
            return new JsonResponse($member->toArray());
        }

        return $this->generateInfoResponse(JsonResponse::HTTP_NOT_FOUND);
    }

    /**
     * @ApiDoc(
     *  section="Projects",
     *  resource=true,
     *  description="Create new project member.",
     *  requirements={
     *      {
     *          "name"="employeeId",
     *          "dataType"="integer",
     *          "description"="Employee id."
     *      },
     *      {
     *          "name"="roleId",
     *          "dataType"="integer",
     *          "description"="Project role id."
     *      }
     *  },
     *  parameters={
     *      {"name"="projectId", "dataType"="integer", "required"=true, "description"="Project id"}
     *  }
     * )
     * @Route("/")
     * @Method({"POST"})
     * @param Request $request
     * @return JsonResponse
     */
    public function createAction(Request $request)
    {
        if ($request->get('projectId') && $project = $this->getProjectById($request->get('projectId'))) {
            if (
                $request->get('employeeId') &&
                $request->get('roleId') &&
                ($employee = $this->getDoctrine()->getRepository('StaffBundle:Employee')->find($request->get('employeeId'))) &&
                ($role = $this->getDoctrine()->getRepository('ProjectsBundle:ProjectRole')->find($request->get('roleId')))
            ) {
                $em = $this->getDoctrine()->getManager();
                $em->persist((new Member())
                    ->setEmployee($employee)
                    ->setRole($role)
                    ->setProject($project)
                    ->setStatus(Member::STATUS_AVAILABLE));
                $em->flush();

                return $this->generateInfoResponse(JsonResponse::HTTP_CREATED);
            }

            return $this->generateInfoResponse(JsonResponse::HTTP_BAD_REQUEST);
        }

        return $this->generateInfoResponse(JsonResponse::HTTP_NOT_FOUND);
    }

    /**
     * @ApiDoc(
     *  section="Projects",
     *  resource=true,
     *  description="Update project member.",
     *  parameters={
     *      {"name"="projectId", "dataType"="integer", "required"="true", "description"="Project id"},
     *      {"name"="id", "dataType"="integer", "required"="true", "description"="Member id"},
     *      {
     *          "name"="roleId",
     *          "dataType"="integer",
     *          "required"="false",
     *          "description"="Project role id."
     *      },
     *      {
     *          "name"="status",
     *          "dataType"="string",
     *          "required"="false",
     *          "pattern"="(available|unavailable)",
     *          "description"="Member status."
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
        if (
            $request->get('projectId') &&
            $this->getProjectById($request->get('projectId')) &&
            $member = $this->getDoctrine()->getRepository('ProjectsBundle:Member')->find($id)
        ) {
            $status = $request->get('status');
            $roleId = $request->get('roleId');
            $role = $this->getDoctrine()->getRepository('ProjectsBundle:ProjectRole')->find($roleId);
            if ($status || $role) {
                $em = $this->getDoctrine()->getManager();
                if ($role = $this->getDoctrine()->getRepository('ProjectsBundle:ProjectRole')->find($roleId)) {
                    $member->setRole($role);
                };
                if ($status) {
                    $member->setStatus($status);
                }
                $em->persist($member);
                $em->flush();

                return $this->generateInfoResponse(JsonResponse::HTTP_CREATED);
            }

            return $this->generateInfoResponse(JsonResponse::HTTP_BAD_REQUEST);
        }

        return $this->generateInfoResponse(JsonResponse::HTTP_NOT_FOUND);
    }

    /**
     * @ApiDoc(
     *  section="Projects",
     *  resource=true,
     *  description="Remove concrete project member.",
     *  parameters={
     *      {"name"="projectId", "dataType"="integer", "required"="true", "description"="Project id"},
     *      {"name"="id", "dataType"="integer", "required"="true", "description"="Member id"}
     *  },
     * )
     * @Route("/{id}/")
     * @Method({"DELETE"})
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    public function deleteAction(Request $request, $id)
    {
        if ($member = $this->getDoctrine()->getRepository('ProjectsBundle:Member')->find($id)) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($member);
            $em->flush();

            return $this->generateInfoResponse(JsonResponse::HTTP_NO_CONTENT);
        }

        return $this->generateInfoResponse(JsonResponse::HTTP_NOT_FOUND);
    }

    private function getProjectById($id)
    {
        return $this->getDoctrine()->getRepository('ProjectsBundle:Project')->find($id);
    }

    /**
     * @return array
     */
    protected function getLikeFiltrationFields()
    {
        return [MemberRepository::FIELD_EMPLOYEE_NAME];
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
            MemberRepository::FIELD_EMPLOYEE_NAME
        ];
    }

    /**
     * @return string
     */
    protected function getDefaultSort()
    {
        return MemberRepository::FIELD_EMPLOYEE_NAME;
    }
}