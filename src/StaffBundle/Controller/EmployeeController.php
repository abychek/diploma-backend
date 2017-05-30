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

    /**
     * @Route("/employees")
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

            return new JsonResponse(['message' => 'Created'], JsonResponse::HTTP_CREATED);
        }

        return new JsonResponse(['message' => 'Invalid arguments'], JsonResponse::HTTP_BAD_REQUEST);
    }

    /**
     * @Route("/employees/{id}")
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

            return new JsonResponse(['message' => 'Updated'], JsonResponse::HTTP_OK);
        }

        return new JsonResponse(['message' => 'Invalid arguments'], JsonResponse::HTTP_BAD_REQUEST);
    }

    /**
     * @Route("/employees/{id}")
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

            return new JsonResponse(['message' => 'Removed'], JsonResponse::HTTP_NO_CONTENT);
        }

        return new JsonResponse(['message' => 'Employee not found'], JsonResponse::HTTP_NOT_FOUND);
    }
}
