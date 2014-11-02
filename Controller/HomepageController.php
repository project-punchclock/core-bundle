<?php
namespace Volleyball\Bundle\UtilityBundle\Controller;

use \Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use \Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use \Symfony\Component\HttpFoundation\Request;
use \Sylius\Bundle\ResourceBundle\Controller\Configuration;
use \Sylius\Bundle\ResourceBundle\Controller\ParametersParser;
use \Sylius\Bundle\ResourceBundle\ExpressionLanguage\ExpressionLanguage;

use \Volleyball\Bundle\ResourceBundle\Form\Type\ContactFormType;

class HomepageController extends \Volleyball\Bundle\UtilityBundle\Controller\UtilityController
{
    public function __construct()
    {
        //$config = $this->get('sylius.controller.configuration_factory');
        $parser = new ParametersParser(new ExpressionLanguage());
        $config = new Configuration(
            $parser,
            'volleyball',
            'carousel',
            'VolleyballResourceBundle:Homepage:index.html.twig',
            'twig',
            array()
        );
        parent::__construct($config);
    }
    
    /**
     * @Route("/", name="homepage")
     * @Template("VolleyballResourceBundle:Homepage:index.html.twig")
     */
    public function indexAction(Request $request)
    {
        if (!$request->get('homepage', false) && $this->get('security.context')->isGranted('ROLE_USER')) {
            $this->forward('VolleyballUserBundle:Dashboard:index');
        }
        
        /*
         * If anonymous user OR dashboard was not implicitly requested
         */
        // Carousel
        $carousel = $this->get('volleyball.repository.carousel')
            ->findOneBySlug('splash');
    
        if (!$carousel) {
            $carousel = $this->get('volleyball.repository.carousel')->createNew();
            $carousel->setName('splash');
            
            for ($i = 1; $i < 4; $i++) {
                $item = $this->get('volleyball.repository.carousel_item')->createNew();
                $item
                    ->setName('item '.$i)
                    ->setCaption('item '.$i)
                    ->setImage('/bundles/volleyballresource/images/carousel/item'.$i.'.jpg');
                $carousel->addItem($item);
            }
        }

        // last username entered by the user
        $lastUsername = (!$this->securityContext->isGranted('ROLE_USER')) ?
                '' :
                $this->securityContext->get(SecurityContext::LAST_USERNAME);

        $csrfToken = $this->container->has('form.csrf_provider')
            ? $this->container->get('form.csrf_provider')->generateCsrfToken('authenticate')
            : null;

        return array(
            'carousel' => $carousel,
            'csrf_token' => $csrfToken,
            'last_username' => $lastUsername
        );
    }

    /**
     * @Route("/about", name="volleyball_about")
     * @Template("VolleyballResourceBundle:Homepage:about.html.twig")
     */
    public function aboutAction()
    {
        return array();
    }

    /**
     * @Route("/help", name="volleyball_help")
     * @Template("VolleyballResourceBundle:Homepage:help.html.twig")
     */
    public function helpAction()
    {
        return array();
    }

    /**
     * @Route("/contact", name="volleyball_contact")
     * @Template("VolleyballResourceBundle:Homepage:contact.html.twig")
     */
    public function contactAction(Request $request)
    {
        $form = $this->createForm(new ContactFormType());

        if ($request->isMethod('POST')) {
            $form->bind($request);

            if ($form->isValid()) {
                $message = \Swift_Message::newInstance()
                    ->setSubject($form->get('subject')->getData())
                    ->setFrom($form->get('email')->getData())
                    ->setTo('volleyball@daviddurost.net')
                    ->setBody(
                        $this->renderView(
                            'VolleyballResourceBundle:Homepage:contact.html.php',
                            array(
                                'ip' => $request->getClientIp(),
                                'name' => $form->get('name')->getData(),
                                'message' => $form->get('message')->getData()
                            )
                        )
                    );

                $this->get('mailer')->send($message);

                $request->getSession()->getFlashBag()->add('success', 'Your email has been sent.');

                return $this->redirect($this->generateUrl('volleyball_contact'));
            }
        }

        return array(
            'form' => $form->createView()
        );
    }
}
