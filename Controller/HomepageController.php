<?php
namespace Volleyball\Bundle\UtilityBundle\Controller;

use \Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use \Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use \Symfony\Component\HttpFoundation\Request;
use \Sylius\Bundle\ResourceBundle\Controller\Configuration;
use \Sylius\Bundle\ResourceBundle\Controller\ParametersParser;
use \Sylius\Bundle\ResourceBundle\ExpressionLanguage\ExpressionLanguage;

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
            $session = $this->get('security.context');
            
            if ($session->isGranted('ROLE_ADMIN')) {
                /**
                 * Admin user
                 */
                $this->forwardToDashboard(
                    ($session->isGranted(' ROLE_SUPER_ADMIN') ? 'ROLE_SUPER_ADMIN' : 'ROLE_ADMIN')
                );
            } else {
                $this->forwardToDashboard('ROLE_USER');
            }
        }
        
        /*
         * If anonymous user OR dashboard was not implicitly requested
         */
        // Carousel
        $carousel = $this->get('doctrine')->getRepository('VolleyballUtilityBundle:Carousel')
            ->findOneBySlug('splash');
    
        if (!$carousel) {
            $carousel = array('items' => array());
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
     * @return type
     */
    public function aboutAction()
    {
        return $this->render('VolleyballUtilityBundle:Homepage:about.html.twig');
    }

    /**
     * @Route("/help", name="volleyball_help")
     * @return type
     */
    public function helpAction()
    {
        return $this->render('VolleyballUtilityBundle:Homepage:help.html.twig');
    }

    /**
     * @Route("/contact", name="volleyball_contact")
     * @return type
     */
    public function contactAction()
    {
        return $this->render('VolleyballUtilityBundle:Homepage:contact.html.twig');
    }
    
    /**
     * Forward to dashboard
     * 
     * @param string $role role
     */
    private function forwardToDashboard($role)
    {
        /**
         * @todo find a cleaner way to match the role to the controller
         *
         * $roles = Yaml::parse($this->locator->locate('security.yml', null, null));
         * $roles = new ArrayCollection($roles['security']['role_hierarchy']);
         */
        $roleEntities = array(
            'ROLE_PASSEL_USER'          =>  'VolleyballPasselBundle:Attendee:dashboard',
            'ROLE_PASSEL_LEADER'        =>  'VolleyballPasselBundle:Leader:dasboard',
            'ROLE_PASSEL_ADMIN'         =>  'VolleyballPasselBundle:Leader:dasboard',
            'ROLE_FACILITY_USER'        =>  'VolleyballFacilityBundle:Faculty:dashboard',
            'ROLE_FACILITY_FACULTY'     =>  'VolleyballFacilityBundle:Faculty:dashboard',
            'ROLE_FACILITY_ADMIN'       =>  'VolleyballFacilityBundle:Faculty:dashboard',
            'ROLE_ORG_USER'             =>  'VolleyballOrganizationBundle:Organization:dashboard',
            'ROLE_ORG_ADMIN'            =>  'VolleyballOrganizationBundle:Organization:dashboard',
            'ROLE_REGION_USER'          =>  'VolleyballOrganizationBundle:Region:dashboard',
            'ROLE_REGION_ADMIN'         =>  'VolleyballOrganizationBundle:Region:dashboard',
            'ROLE_ADMIN'                =>  'VolleyballUserBundle:Admin:dashboard',
            'ROLE_SUPER_ADMIN'          =>  'VolleyballUserBundle:Admin:dashboard'
        );
        $this->forward($roleEntities[$role]);
    }
}
