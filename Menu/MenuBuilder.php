<?php
namespace ProjectPunchclock\Bundle\CoreBundle\Menu;

use \Symfony\Component\HttpFoundation\Request;
use \Symfony\Component\Translation\TranslatorInterface;
use \Symfony\Component\Security\Core\SecurityContextInterface;
use \Symfony\Component\ExpressionLanguage\Expression;

use ProjectPunchclock\Bundle\CoreBundle\Menu\BaseBuilder;

class MenuBuilder extends BaseBuilder
{
    /**
     * Construct
     * @param \Knp\Menu\FactoryInterface $factory
     * @param SecurityContextInterface $securityContext
     * @param TranslatorInterface $translator
     */
    public function __construct(
        \Knp\Menu\FactoryInterface $factory,
        SecurityContextInterface $securityContext,
        TranslatorInterface $translator
    ) {
        parent::__construct($factory, $securityContext, $translator);
    }
    
    /**
     * Navigation menu for a non auth'd user (guest)
     * @param  Symfony\Component\HttpFoundation\Request $request
     * @return MenuItem
     */
    public function createNonAuthMenu(\Symfony\Component\HttpFoundation\Request $request)
    {
        $menu = $this->factory->createItem('root');
        $menu->setChildrenAttribute('class', 'nav pull-right');

        $menu->addChild(
            'home',
            array('route' =>  'homepage')
        );

        $menu->addChild(
            'sign in',
            array('route' =>  'fos_user_security_login')
        );

        $menu->addChild(
            'sign up',
            array('route' =>  'fos_user_registration_register')
        );

        $menu->addChild(
            'about',
            array('route' => 'about')
        );

        $menu->addChild(
            'contact',
            array('route' => 'contact')
        );

        return $menu;
    }

    /**
     * Navigation root menu for auth'd user
     * @param  Symfony\Component\HttpFoundation\Request $request
     * @return MenuItem
     */
    public function createMainMenu(\Symfony\Component\HttpFoundation\Request $request)
    {
        if ($this->securityContext->isGranted('ROLE_AUTHENTICATED_ANONYMOUSLY') ||
            !$this->securityContext->isGranted('ROLE_USER')) {
            return $this->createNonauthMenu($request);
        }

        $menu = $this->factory->createItem('root');
        $menu->setChildrenAttribute('class', 'nav pull-right');

        $menu->addChild(
            'home',
            array('route' => 'homepage')
        );

        $menu->addChild($this->profileMenu($request));
        
        /////////////////////////////////////////////////////////
        ////AUTH MENU////////////////////////////////////////////
        /////////////////////////////////////////////////////////
        if ($this->securityContext->isGranted('ROLE_USER')) {
            $menu->addChild(
                'sign out',
                array('route' => 'fos_user_security_logout')
            );
        } else {
            $menu->addChild(
                'sign up',
                array('route' => 'fos_user_register_index')
            );

            $menu->addChild(
                'sign in',
                array('route' => 'fos_user_security_login')
            );
        }

        return $menu;
    }

    /**
     * Profile menu
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return MenuItem 
     */
    public function profileMenu(\Symfony\Component\HttpFoundation\Request $request)
    {
        $menu = $this->factory->createItem('profile')
            ->setAttribute('dropdown', true);

        $menu->addChild(
            'view profile',
            array(
                'route' => 'fos_user_profile_show',
                'routeParameters' => array(
                    'slug' => $this->securityContext->getToken()->getUser()->getSlug()
                )
            )
        );

        $menu->addChild(
            'edit profile',
            array(
                'route' => 'fos_user_profile_edit',
                'routeParameters' => array(
                    'slug' => $this->securityContext->getToken()->getUser()->getSlug()
                )
            )
        );

        $menu->addChild(
            'change password',
            array('route' => 'fos_user_change_password')
        );
        
        return $menu;
    }
}
