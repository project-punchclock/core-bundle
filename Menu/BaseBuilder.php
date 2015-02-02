<?php
namespace ProjectPunchclock\Bundle\CoreBundle\Menu;

use \JMS\TranslationBundle\Annotation\Ignore;

abstract class BaseBuilder
{
    /**
     * Factory
     * @var \Knp\Menu\FactoryInterface
     */
    protected $factory;

    /**
     * Security context
     * @var \Symfony\Component\Security\Core\SecurityContextInterface 
     */
    protected $securityContext;

    /**
     * Translator
     * @var \Symfony\Component\Translation\TranslatorInterface
     */
    protected $translator;

    /**
     * Request
     * @var \Symfony\Component\HttpFoundation\Request 
     */
    protected $request;

    /**
     * Construct
     * @param \Knp\Menu\FactoryInterface $factory
     * @param \Symfony\Component\Security\Core\SecurityContextInterface $securityContext
     * @param \Symfony\Component\Translation\TranslatorInterface $translator
     */
    public function __construct(
        \Knp\Menu\FactoryInterface $factory,
        \Symfony\Component\Security\Core\SecurityContextInterface $securityContext,
        \Symfony\Component\Translation\TranslatorInterface $translator
    ) {
        $this->factory = $factory;
        $this->securityContext = $securityContext;
        $this->translator = $translator;
    }

    /**
     * Set request
     * @param \Symfony\Component\HttpFoundation\Request $request
     */
    public function setRequest(\Symfony\Component\HttpFoundation\Request $request)
    {
        $this->request = $request;
    }

    /**
     * Translate
     * @param string $label
     * @param array $parameters
     * @return string
     */
    protected function translate($label, $parameters = array())
    {
        return $this->translator->trans(/** @Ignore */ $label, $parameters, 'menu');
    }
}
