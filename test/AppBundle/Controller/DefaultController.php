<?php

namespace AppBundle\Controller;

use Ekreative\RedmineLoginBundle\Form\LoginType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;

class DefaultController extends Controller
{
    /**
     * @Route("/admin", name="homepage")
     */
    public function indexAction()
    {
        return $this->render('default/index.html.twig', [
            'projects' => json_decode($this->get('ekreative_redmine_login.client_provider')->get($this->getUser())->get('projects.json')->getBody(), true)['projects']
        ]);
    }
}
