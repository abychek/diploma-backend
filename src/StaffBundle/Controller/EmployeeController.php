<?php

namespace StaffBundle\Controller;

use AppBundle\Controller\RestController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use StaffBundle\Entity\Employee;
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
     * @Route("/")
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
     * @Route("/{id}/")
     * @Method({"PUT"})
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    public function updateAction(Request $request, $id)
    {
        $employee = $this->getDoctrine()->getRepository('StaffBundle:Employee')->find($id);

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

    /**
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
}
