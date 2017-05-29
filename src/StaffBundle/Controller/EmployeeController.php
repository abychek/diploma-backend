<?php

namespace StaffBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;


class EmployeeController extends Controller
{
    /**
     * @Route("/employees")
     */
    public function listAction()
    {
        $result = [];
        $employees = $this->getDoctrine()->getRepository('StaffBundle:Employee')->findAll();
        foreach ($employees as $employee) {
            $result[] = $employee->toArray();
        }

        return new JsonResponse($result);
    }
}
