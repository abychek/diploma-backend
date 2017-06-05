<?php

namespace StaffBundle\Controller;

use AppBundle\Controller\RestController;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use StaffBundle\Entity\Employee;
use StaffBundle\Repository\EmployeeRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class EmployeeController
 * @package StaffBundle\Controller
 * @Route("/employees")
 */
class EmployeeController extends RestController
{
    /**
     * @ApiDoc(
     *  section="Employees",
     *  resource=true,
     *  description="Return list of employees.",
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
     *      },
     *      {
     *          "name"="projects",
     *          "dataType"="array",
     *          "pattern"="{project_id1},{project_id2},{project_id3}, ...",
     *          "description"="Filtrate employees by Employees."
     *      },
     *      {
     *          "name"="skills",
     *          "dataType"="array",
     *          "pattern"="{technology_id1},{technology_id2},{technology_id3}, ...",
     *          "description"="Filtrate employees by Technologies."
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
        $employees = $this->getDoctrine()->getRepository('StaffBundle:Employee')->getSortedByName($options);
        foreach ($employees as $employee) {
            $result[] = $employee->toArray();
        }

        return new JsonResponse($result);
    }

    /**
     * @ApiDoc(
     *  section="Employees",
     *  resource=true,
     *  description="Return concrete employee.",
     *  parameters={
     *      {"name"="id", "dataType"="integer", "required"=true, "description"="Employee id"}
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
        if ($employee = $this->getDoctrine()->getRepository('StaffBundle\Entity\Employee')->find($id)) {
            return new JsonResponse($employee->toArray());
        }

        return $this->generateInfoResponse(JsonResponse::HTTP_NOT_FOUND);
    }

    /**
     * @ApiDoc(
     *  section="Employees",
     *  resource=true,
     *  description="Create new Employee.",
     *  requirements={
     *      {
     *          "name"="name",
     *          "dataType"="string",
     *          "description"="Employee name."
     *      },
     *      {
     *          "name"="position",
     *          "dataType"="integer",
     *          "description"="Position id."
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
        $em = $this->getDoctrine()->getManager();
        $name = $request->get('name');
        $position = $this->getDoctrine()->getRepository('StaffBundle:Position')->find($request->get('position'));
        if ($name && $position) {
            $em->persist((new Employee())
                ->setName($name)
                ->setPosition($position)
                ->setStatus(Employee::STATUS_AVAILABLE));
            $em->flush();

            return $this->generateInfoResponse(JsonResponse::HTTP_CREATED);
        }

        return $this->generateInfoResponse(JsonResponse::HTTP_BAD_REQUEST);
    }

    /**
     * @ApiDoc(
     *  section="Employees",
     *  resource=true,
     *  description="Update employee.",
     *  parameters={
     *      {"name"="id", "dataType"="integer", "required"=true, "description"="Employee id"},
     *      {
     *          "name"="name",
     *          "dataType"="string",
     *          "required"=false,
     *          "description"="Employee name."
     *      },
     *      {
     *          "name"="position",
     *          "dataType"="integer",
     *          "required"=false,
     *          "description"="Position id."
     *      },
     *      {
     *          "name"="status",
     *          "dataType"="string",
     *          "required"=false,
     *          "pattern"="(available|unavailable)",
     *          "description"="Employee status."
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
        if ($employee = $this->getDoctrine()->getRepository('StaffBundle:Employee')->find($id)) {
            $name = $request->get('name', $employee->getName());
            $position = $this->getDoctrine()->getRepository('StaffBundle:Position')->find($request->get('position', $employee->getPosition()));
            $status = $request->get('status', $employee->getStatus());
            if ($name || $position || $status) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($employee->setName($name)->setPosition($position)->setStatus($status));
                $em->flush();

                return $this->generateInfoResponse(JsonResponse::HTTP_OK);
            }

            return $this->generateInfoResponse(JsonResponse::HTTP_BAD_REQUEST);
        }

        return $this->generateInfoResponse(JsonResponse::HTTP_NOT_FOUND);
    }

    /**
     * @ApiDoc(
     *  section="Employees",
     *  resource=true,
     *  description="Remove concrete employee.",
     *  parameters={
     *      {"name"="id", "dataType"="integer", "required"=true, "description"="Project id"}
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
        if ($employee = $this->getDoctrine()->getRepository('StaffBundle:Employee')->find($id)) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($employee);
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
        return [EmployeeRepository::FIELD_NAME];
    }

    /**
     * @return array
     */
    protected function getInFiltrationFields()
    {
        return [EmployeeRepository::FIELD_PROJECTS, EmployeeRepository::FIELD_SKILLS];
    }

    /**
     * @return array
     */
    protected function getAllowedToSort()
    {
        return [
            EmployeeRepository::FIELD_NAME
        ];
    }

    /**
     * @return string
     */
    protected function getDefaultSort()
    {
        return EmployeeRepository::FIELD_NAME;
    }
}
