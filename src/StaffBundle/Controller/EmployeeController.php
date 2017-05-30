<?php

namespace StaffBundle\Controller;

use AppBundle\Controller\RestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class EmployeeController extends RestController
{
    /**
     * @Route("/employees")
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
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    public function getAction(Request $request, $id)
    {
        if ($employee = $this->getDoctrine()->getRepository('StaffBundle\Entity\Employee')->find($id)) {
            return new JsonResponse($employee->toArray());
        }

        throw new NotFoundHttpException('Employee not found.');
    }
}
