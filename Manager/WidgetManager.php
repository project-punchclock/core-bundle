<?php
namespace Volleyball\Bundle\UtilityBundle\Manager;

use \Symfony\Component\DependencyInjection\ContainerInterface;
use \Doctrine\Common\Collections\ArrayCollection;

class WidgetManager
{
    /**
     * Widgets
     * @var ArrayCollection
     */
    protected $widgets;
    
    /**
     * Container
     * @var ContainerInterfaces 
     */
    protected $container;
    
    /**
     * Construct
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->widgets = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Add widget
     * @param \Volleyball\Bundle\UtilityBundle\Widget\DashbaordWidget $widget
     */
    public function addWidget(\Volleyball\Bundle\UtilityBundle\Widget\DashboardWidget $widget)
    {
        $this->widgets->add($widget);
    }
    
    /**
     * Get widgets
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getWidgets()
    {
        if (0 == count($this->widgets)) {
            $this->widgets = $this->container->get('volleyball.repository.widget')->findAll();
        }
        
        return $this->widgets;
    }
    
    public function getUserWidgets($user = null)
    {
        if (is_null($user)) {
            $user = $this->container->get('security.context')->getToken()->getUser();
        }
        
        $widgets = new ArrayCollection();
        foreach ($this->getWidgets() as $widget) {
            var_dump($widget->roles());
            die('...');
           
            $widgets[] = (empty(array_intersect($widget->getRoles(), $user->getRoles()))) ? $widget : null;
        }
        
        return $widgets;
    }
}
