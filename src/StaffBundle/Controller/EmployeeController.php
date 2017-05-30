<?php

namespace StaffBundle\Controller;

use AppBundle\Controller\RestController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use StaffBundle\Entity\Employee;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class EmployeeController extends RestController
{
    /**
     * @Route("/employees")
     * @Method({"GET"})
     * @param Request $request
     * @return JsonResponse
     */
    public function listAction(Request $request)
    {
        $result = [];
        $options = $this->handlePagination($request);
        $employees = $this->getDoctrine()->getRepository('StaffBundle:Employee')->getSortedByName($options);
        foreach ($employees as $employee) {
            $result[] = $employee->toArray();
        }

        return new JsonResponse($result);
    }

    /**
     * @Route("/employees/{id}")
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

        return new JsonResponse(['message' => 'Employee not found.'], JsonResponse::HTTP_NOT_FOUND);
    }

    public function createAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $em->persist((new Employee())->setName($request->get('name'))->setStatus(Employee::STATUS_AVAILABLE));
        $em->flush();

        return new JsonResponse(['message' => 'Created'], JsonResponse::HTTP_CREATED);
    }
}
