<?php
namespace ProjectPunchclock\Bundle\CoreBundle\Menu;

use \ProjectPunchclock\Bundle\CoreBundle\Menu\MenuBuilder;

class AdminMenuBuilder extends MenuBuilder
{
    /**
     * Create admin menu
     * @param \ProjectPunchclock\Bundle\CoreBundle\Menu\Request $request
     * @return Menuitem
     */
    public function createAdminMenu(Request $request)
    {
        $menu = $this->factory->createItem('root');
        $menu->setChildrenAttribute('class', 'nav pull-right');
        
        $menu->addChild(
            'home',
            array('route' =>  'homepage')
        );
        
        $this->addChild($this->profileMenu($request));
        
        return $menu;
    }
}
