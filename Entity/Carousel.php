<?php
namespace Volleyball\Bundle\UtilityBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;

use Volleyball\Component\Utility\Model\Carousel as BaseCarousel;
use Volleyball\Bundle\UtilityBundle\Entity\CarouselItem;
use Volleyball\Bundle\UtilityBundle\Traits\EntityBootstrapTrait;
use Volleyball\Bundle\UtilityBundle\Traits\SluggableTrait;
use Volleyball\Bundle\UtilityBundle\Traits\TimestampableTrait;

/**
 * @ORM\Entity(repositoryClass="Volleyball\Bundle\UtilityBundle\Repository\CarouselRepository")
 * @ORM\Table(name="carousel")
 */
class Carousel extends BaseCarousel
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
     * {@inheritdoc}
     * @ORM\OneToMany(targetEntity="CarouselItem", mappedBy="carousel")
     */
    protected $items;

    /**
     * {@inheritdoc}
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * {@inheritdoc}
     */
    public function setItems(array $items)
    {
        if (!$items instanceof ArrayCollection) {
            $items = new ArrayCollection($items);
        }

        $this->items = $items;

        return $this;
    }

    /**
     * Has items
     *
     * @return boolean
     */
    public function hasItems()
    {
        return !$this->items->isEmpty();
    }

    /**
     * {@inheritdoc}
     */
    public function getItem($item)
    {
        return $this->items->get($item);
    }
        
    /**
     * Remove a item
     *
     * @param CarouselItem|String $item item
     *
     * @return self
     */
    public function removeItem(CarouselItem $item)
    {
        unset($this->items[$item]);

        return $this;
    }
}
