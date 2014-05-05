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
     
    protected $name;
    
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
