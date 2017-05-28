<?php

namespace LinkedinBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class SecurityController extends Controller
{
    /**
     * @Route("/auth", name="linkedin_auth")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function linkedinAuthAction()
    {
        $url = $this->get('linkedin.manager')->getLoginUrl();

        return $this->redirect($url);
    }

    /**
     * @Route("/auth/callback", name="linkedin_auth_callback")
     */
    public function linkedinAuthCallbackAction(Request $request)
    {
        dump($request);die;
    }
}