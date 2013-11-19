<?php

namespace Majes\CoreBundle\Controller;

use Majes\CoreBundle\Controller\SystemController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;

class AuthController extends Controller implements SystemController
{


    public function loginAction()
    {
        /*
         * The action's view can be rendered using render() method
         * or @Template annotation as demonstrated in DemoController.
         *
         */

        $request = $this->getRequest();
        $session = $request->getSession();

        // get the login error if there is one
        $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
        $session->remove(SecurityContext::AUTHENTICATION_ERROR);

        return $this->render('MajesCoreBundle:Auth:login.html.twig', array('auth' => true));
    }

    public function loginCheckAction()
    {
        /*
         * The action's view can be rendered using render() method
         * or @Template annotation as demonstrated in DemoController.
         *
         */
        return $this->render('MajesCoreBundle:Auth:login.html.twig');
    }
}
