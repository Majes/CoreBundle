<?php

namespace Majes\CoreBundle\Controller;

use Majes\CoreBundle\Controller\SystemController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ExceptionController extends Controller implements SystemController
{
    public function notfoundAction()
    {
        /*
         * The action's view can be rendered using render() method
         * or @Template annotation as demonstrated in DemoController.
         *
         */
        return $this->render('MajesCoreBundle:Exception:notfound.html.twig');
    }

    
}
