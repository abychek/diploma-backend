<?php

namespace ProjectsBundle\Controller;

use AppBundle\Controller\RestController;
use AppBundle\Repository\ResourceRepository;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
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
     * @ApiDoc(
     *  section="Projects",
     *  resource=true,
     *  description="Return list of projects.",
     *  filters={
     *      {"name"="size", "dataType"="integer", "description"="Size of returned data"},
     *      {"name"="from", "dataType"="integer", "description"="Start position of returned data"},
     *      {
     *          "name"="sort",
     *          "dataType"="string",
     *          "pattern"="field:(title|started_at|finished_at); strategy:(ASC|DESC)",
     *          "description"="Sorted field and strategy ({field}:{strategy})"
     *      },
     *      {
     *          "name"="title",
     *          "dataType"="string",
     *          "description"="Filtrate projects by Title."
     *      },
     *      {
     *          "name"="started_at",
     *          "dataType"="date",
     *          "pattern"="dd.mm.YYYY",
     *          "description"="Filtrate projects by Start date."
     *      },
     *      {
     *          "name"="finished_at",
     *          "dataType"="date",
     *          "pattern"="dd.mm.YYYY",
     *          "description"="Filtrate projects by Start date."
     *      },
     *      {
     *          "name"="members",
     *          "dataType"="array",
     *          "pattern"="{employee_id1},{employee_id2},{employee_id3}, ...",
     *          "description"="Filtrate projects by Employees."
     *      },
     *      {
     *          "name"="technologies",
     *          "dataType"="array",
     *          "pattern"="{technology_id1},{technology_id2},{technology_id3}, ...",
     *          "description"="Filtrate projects by Technologies."
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
        $projects = $this->getDoctrine()->getRepository('ProjectsBundle:Project')->getByOptions($options);
        foreach ($projects as $project) {
            $result[] = $project->toArray();
        }

        return new JsonResponse($result);
    }

    /**
     * @ApiDoc(
     *  section="Projects",
     *  resource=true,
     *  description="Return concrete project.",
     *  parameters={
     *      {"name"="id", "dataType"="integer", "required"=true, "description"="Project id"}
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
        if ($project = $this->getDoctrine()->getRepository('ProjectsBundle:Project')->find($id)) {
            return new JsonResponse($project->toArray());
        }

        return $this->generateInfoResponse(JsonResponse::HTTP_NOT_FOUND);
    }

    /**
     * @ApiDoc(
     *  section="Projects",
     *  resource=true,
     *  description="Create new project.",
     *  requirements={
     *      {
     *          "name"="title",
     *          "dataType"="string",
     *          "description"="Project name."
     *      },
     *      {
     *          "name"="descriptions",
     *          "dataType"="text",
     *          "description"="Project description."
     *      }
     *  },
     *  parameters={
     *      {
     *          "name"="startDate",
     *          "dataType"="date",
     *          "required"=false,
     *          "pattern"="dd.mm.YYYY",
     *          "description"="Project start date."
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
     * @ApiDoc(
     *  section="Projects",
     *  resource=true,
     *  description="Update project.",
     *  parameters={
     *      {"name"="id", "dataType"="integer", "required"=true, "description"="Project id"},
     *      {
     *          "name"="title",
     *          "dataType"="string",
     *          "required"=false,
     *          "description"="Project name."
     *      },
     *      {
     *          "name"="descriptions",
     *          "dataType"="text",
     *          "required"=false,
     *          "description"="Project description."
     *      },
     *      {
     *          "name"="startDate",
     *          "dataType"="date",
     *          "required"=false,
     *          "pattern"="dd.mm.YYYY",
     *          "description"="Project start date."
     *      },
     *      {
     *          "name"="finishDate",
     *          "dataType"="date",
     *          "required"=false,
     *          "pattern"="dd.mm.YYYY",
     *          "description"="Project finish date."
     *      },
     *      {
     *          "name"="status",
     *          "dataType"="string",
     *          "required"=false,
     *          "pattern"="(available|unavailable)",
     *          "description"="Project status."
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
     * @ApiDoc(
     *  section="Projects",
     *  resource=true,
     *  description="Remove concrete project.",
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
        return [
            ProjectRepository::FIELD_MEMBERS,
            ProjectRepository::FIELD_TECHNOLOGIES
        ];
    }

    /**
     * @return array
     */
    protected function getAllowedToSort()
    {
        return [
            ProjectRepository::FIELD_TITLE,
            ProjectRepository::FIELD_STARTED_AT,
            ProjectRepository::FIELD_FINISHED_AT
        ];
    }

    /**
     * @return string
     */
    protected function getDefaultSort()
    {
        return ProjectRepository::FIELD_TITLE;
    }
}
