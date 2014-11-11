<?php
namespace Volleyball\Bundle\UtilityBundle\Controller;

use \Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use \Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use \Symfony\Component\HttpFoundation\Request;

class DashboardController extends \Volleyball\Bundle\UtilityBundle\Controller\UtilityController
{
    protected $roleEntities= array(
            'ROLE_PASSEL_USER'          =>  'VolleyballPasselBundle:Attendee:dashboard',
            'ROLE_PASSEL_LEADER'        =>  'VolleyballPasselBundle:Leader:dasboard',
            'ROLE_PASSEL_ADMIN'         =>  'VolleyballPasselBundle:Leader:dasboard',
            'ROLE_FACILITY_USER'        =>  'VolleyballFacilityBundle:Faculty:dashboard',
            'ROLE_FACILITY_FACULTY'     =>  'VolleyballFacilityBundle:Faculty:dashboard',
            'ROLE_FACILITY_ADMIN'       =>  'VolleyballFacilityBundle:Faculty:dashboard',
            'ROLE_ADMIN'                =>  'VolleyballUserBundle:Admin:dashboard',
            'ROLE_SUPER_ADMIN'          =>  'VolleyballUserBundle:Admin:dashboard'
        );
    
    public function indexAction(Request $request)
    {
        $arr = array_reverse($this->roleEntities, true);
        foreach ($arr as $role => $path) {
            if ($this->securityContext->isGranted($role)) {
                $this->forward($path)
            }
        }
        $this->forward(
            'VolleyballUtilityBundle:Homepage:index',
            array('homepage' => true)
        );
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
        $this->roleEntities = array(
            'ROLE_PASSEL_USER'          =>  'VolleyballPasselBundle:Attendee:dashboard',
            'ROLE_PASSEL_LEADER'        =>  'VolleyballPasselBundle:Leader:dasboard',
            'ROLE_PASSEL_ADMIN'         =>  'VolleyballPasselBundle:Leader:dasboard',
            'ROLE_FACILITY_USER'        =>  'VolleyballFacilityBundle:Faculty:dashboard',
            'ROLE_FACILITY_FACULTY'     =>  'VolleyballFacilityBundle:Faculty:dashboard',
            'ROLE_FACILITY_ADMIN'       =>  'VolleyballFacilityBundle:Faculty:dashboard',
            'ROLE_ADMIN'                =>  'VolleyballUserBundle:Admin:dashboard',
            'ROLE_SUPER_ADMIN'          =>  'VolleyballUserBundle:Admin:dashboard'
        );
        $this->forward($this->roleEntities[$role]);
    }
}
