<?php
namespace Volleyball\Bundle\UtilityBundle\Traits;

trait EntityBootstrapTrait
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * Get id
     * 
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }
}
