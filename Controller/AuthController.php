<?php

namespace Majes\CoreBundle\Controller;

use Majes\CoreBundle\Controller\SystemController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\Request;

class AuthController extends Controller implements SystemController
{


    public function loginAction(Request $request)
    {
        /*
         * The action's view can be rendered using render() method
         * or @Template annotation as demonstrated in DemoController.
         *
         */

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

    public function forgotAction(Request $request)
    {
        /*
         * The action's view can be rendered using render() method
         * or @Template annotation as demonstrated in DemoController.
         *
         */


        $em = $this->getDoctrine()->getManager();

        if($request->getMethod() == 'POST'){
            $usertochange = $em->getRepository('MajesCoreBundle:User\User')->findOneByUsername($request->request->get('_username'));
            if(is_object($usertochange)){
                $characters = 'abcdefghijklmnopqrstuvwxyz0123456789';
                $newpassword = '';
                for ($i = 0; $i < 8; $i++) {
                    $newpassword .= $characters[rand(0, strlen($characters) - 1)];
                }

                $factory = $this->container->get('security.encoder_factory');
                $encoder = $factory->getEncoder($usertochange);
                $pwd = $encoder->encodePassword($newpassword, $usertochange->getSalt());

                $usertochange->setPassword($pwd);

                $em->flush();

                $transport = \Swift_MailTransport::newInstance();

                $mailer = \Swift_Mailer::newInstance($transport);

                $message = \Swift_Message::newInstance()
                    ->setSubject($this->_translator->trans('Reinitialisation du mot de passe', array(), 'admin'))
                    ->setFrom(array('dev@neomajes.com' => 'Neomajes'))
                    ->setTo($usertochange->getEmail())
                    ->setBody('Bonjour,<br/><br/><strong>Vous avez oublié ou perdu votre mot de passe pour accéder ?</strong><br/>Votre nouveau mot de passe: '.$newpassword.'<br/><br/>Cordialement,<br/><br/>Neomajes', 'text/html');

                $result=$mailer->send($message);
            }

            return $this->redirect($this->get('router')->generate('_admin_login'));
        }

        return $this->render('MajesCoreBundle:Auth:forgot.html.twig', array('auth' => true));
    }
}
