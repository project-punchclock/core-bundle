<?php
namespace Volleyball\Bundle\UtilityBundle\Widget;

use \Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use \Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use \Symfony\Component\DependencyInjection\ContainerInterface;
use \Doctrine\Common\Collections\ArrayCollection;

class DashboardWidget extends \Volleyball\Bundle\UtilityBundle\Entity\Widget
{
    /**
     * Command
     * @var ContainerAwareCommand $command
     */
    protected $command;
    
    /**
     * Construct
     * @param string $command
     * @param string $controller
     * @param ContainerInterface $container
     */
    public function __construct($command, $controller, ContainerInterface $container, $roles = array())
    {
        parent::construct();
        
        $this->command = $command;
        $this->command->setContainer($container);
        $this->controller = $controller;
        $this->roles = new ArrayCollection($roles);
    }
    
    /**
     * Get command
     * @return ContainerAwareCommand
     */
    public function getCommand()
    {
        return $this->command;
    }
    
    /**
     * Resoved controller method
     * @return string
     * @throws \Exception
     */
    public function resolvedController()
    {
        $reader = new \Doctrine\Common\Annotations\AnnotationReader();
        $method = new \ReflectionMethod($this->controller, 'widgetAction');
        $annotations = $reader->getMethodAnnotations($method);
        
        foreach ($annotations as $annotation) {
            if ($annotation instanceof Route) {
                if (empty($annotation)) {
                    throw new \Exception('The name is not configured with the annotation.');
                }
                return $annotation->getName();
            }
        }
        throw new \Exception('There is no route annotation found.');
    }
}
