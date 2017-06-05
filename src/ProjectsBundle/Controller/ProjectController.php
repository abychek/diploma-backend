<?php

namespace ProjectsBundle\Controller;

use AppBundle\Controller\RestController;
use AppBundle\Repository\ResourceRepository;
use ProjectsBundle\Entity\Project;
use ProjectsBundle\Repository\ProjectRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class ProjectsController
 * @package ProjectsBundle\Controller
 * @Route("/projects")
 */
class ProjectController extends RestController
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
        $options = $this->handleOptions($request);
        $projects = $this->getDoctrine()->getRepository('ProjectsBundle:Project')->getByOptions($options);
        foreach ($projects as $project) {
            $result[] = $project->toArray();
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
        if ($project = $this->getDoctrine()->getRepository('ProjectsBundle:Project')->find($id)) {
            return new JsonResponse($project->toArray());
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
        $title = $request->get('title');
        $description = $request->get('description');
        $startDate = new \DateTime($request->get('startDate', 'now'));
        if ($title && $description && $startDate) {
            $em->persist((new Project())
                ->setTitle($title)
                ->setDescription($description)
                ->setStartDate($startDate)
                ->setStatus(Project::STATUS_AVAILABLE));
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
        if ($project = $this->getDoctrine()->getRepository('ProjectsBundle:Project')->find($id)) {
            $title = $request->get('title', $project->getTitle());
            $description = $request->get('description', $project->getDescription());
            $startDate = $request->get('startDate', $project->getStartDate());
            $finishDate = $request->get('finishDate', $project->getFinishDate());
            $status = $request->get('status', $project->getStatus());
            if ($title || $description || $status) {
                $em = $this->getDoctrine()->getManager();
                $project->setTitle($title);
                $project->setDescription($description);
                $project->setStartDate($startDate);
                $project->setFinishDate($finishDate);
                $project->setStatus($status);
                $em->persist($project);
                $em->flush();

                return $this->generateInfoResponse(JsonResponse::HTTP_OK);
            }

            return $this->generateInfoResponse(JsonResponse::HTTP_BAD_REQUEST);
        }

        return $this->generateInfoResponse(JsonResponse::HTTP_NOT_FOUND);
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
        if ($project = $this->getDoctrine()->getRepository('ProjectsBundle:Project')->find($id)) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($project);
            $em->flush();

            return $this->generateInfoResponse(JsonResponse::HTTP_NO_CONTENT);
        }

        return $this->generateInfoResponse(JsonResponse::HTTP_NOT_FOUND);
    }

    protected function handleOptions(Request $request)
    {
        $options = parent::handleOptions($request);
        if ($request->query->has(ProjectRepository::FIELD_STARTED_AT)) {
            $options[ProjectRepository::FIELD_STARTED_AT] = new \DateTime($request->query->get(ProjectRepository::FIELD_STARTED_AT));
        }
        if ($request->query->has(ProjectRepository::FIELD_FINISHED_AT)) {
            $options[ProjectRepository::FIELD_STARTED_AT] = new \DateTime($request->query->get(ProjectRepository::FIELD_FINISHED_AT));
        }

        return $options;
    }

    /**
     * @return array
     */
    protected function getLikeFiltrationFields()
    {
        return [ProjectRepository::FIELD_TITLE];
    }

    /**
     * @return array
     */
    protected function getInFiltrationFields()
    {
        return [ProjectRepository::FIELD_MEMBERS, ProjectRepository::FIELD_TECHNOLOGIES];
    }
}
