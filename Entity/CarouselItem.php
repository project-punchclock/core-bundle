<?php
namespace Volleyball\Bundle\UtilityBundle\Entity;

use \Doctrine\ORM\Mapping as ORM;
use \Gedmo\Mapping\Annotation as Gedmo;
use \Symfony\Component\Validator\Constraints as Assert;
use \Doctrine\Common\Collections\ArrayCollection;

use \Volleyball\Bundle\UtilityBundle\Traits\EntityBootstrapTrait;
use \Volleyball\Bundle\UtilityBundle\Traits\SluggableTrait;
use \Volleyball\Bundle\UtilityBundle\Traits\TimestampableTrait;

/**
* @ORM\Entity(repositoryClass="Volleyball\Bundle\UtilityBundle\Repository\CarouselItemRepository")
* @ORM\Table(name="carousel_item")
*/
class CarouselItem
{
    use EntityBootstrapTrait;
    use SluggableTrait;
    use TimestampableTrait;
    
    /**
     * @ORM\Column(type="string")
     * @Assert\Length(
     *      min = "1",
     *      max = "250",
     *      minMessage = "Name must be at least {{ limit }} characters length",
     *      maxMessage = "Name cannot be longer than {{ limit }} characters length"
     * )
     * @var string
     */
    protected $name;
    
    /**
     * @ORM\Column(type="string")
     * @Assert\Length(
     *      min = "1",
     *      max = "30",
     *      minMessage = "Caption must be at least {{ limit }} characters length",
     *      maxMessage = "Caption cannot be longer than {{ limit }} characters length"
     * )
     * @var string
     */
    protected $caption;
    
    /**
     * @ORM\Column(type="string")
     * @Assert\Url()
     * @Assert\Length(
     *      min = "1",
     *      max = "30",
     *      minMessage = "Caption must be at least {{ limit }} characters length",
     *      maxMessage = "Caption cannot be longer than {{ limit }} characters length"
     * )
     * @var string
     */
    protected $image;
    
    /**
     * @ORM\ManyToOne(targetEntity="Volleyball\Bundle\UtilityBundle\Entity\Carousel", inversedBy="carousel_items")
     * @ORM\JoinColumn(name="carousel_id", referencedColumnName="id")
     */
    protected $carousel;
    
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->name;
    }
    
    /**
     * {@inheritdoc}
     */
    public function setName($name)
    {
        $this->name = $name;
        
        return $this;
    }

    /**
     * Get caption
     * 
     * @return string
     */
    public function getCaption()
    {
        return $this->caption;
    }

    /**
     * Set caption
     * 
     * @param string $caption caption
     * 
     * @return CarouselItem
     */
    public function setCaption($caption)
    {
        $this->caption = $caption;

        return $this;
    }

    /**
     * Get image
     * 
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set image url
     * 
     * @param string $image url
     * 
     * @return string
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }
    
    /**
     * Get carousel
     * @return \Volleyball\Bundle\UtilityBundle\Entity\Carousel
     */
    public function getCarousel()
    {
        return $this->carousel;
    }
    
    /**
     * Set carousel
     * @param \Volleyball\Bundle\UtilityBundle\Entity\Carousel $carousel
     * @return \Volleyball\Bundle\UtilityBundle\Entity\CarouselItem
     */
    public function setCarousel(\Volleyball\Bundle\UtilityBundle\Entity\Carousel $carousel)
    {
        $this->carousel = $carousel;
        
        return $this;
    }
}
