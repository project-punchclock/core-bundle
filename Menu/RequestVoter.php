<?php
namespace ProjectPunchclock\Bundle\CoreBundle\Menu;
 
class RequestVoter implements \Knp\Menu\Matcher\Voter\VoterInterface
{
    /**
     * Container
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    private $container;
 
    /**
     * Construct
     * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
     */
    public function __construct(\Symfony\Component\DependencyInjection\ContainerInterface $container)
    {
        $this->container = $container;
    }
 
    /**
     * Match item
     * @param \Knp\Menu\ItemInterface $item
     * @return boolean
     */
    public function matchItem(\Knp\Menu\ItemInterface $item)
    {
        if ($item->getUri() === $this->container->get('request')->getRequestUri()) {
            return true;
        } elseif ($item->getUri() !== '/' && (substr($this->container->get('request')->getRequestUri(), 0, strlen($item->getUri())) === $item->getUri())) {
            return true;
        }
        
        return null;
    }
}
