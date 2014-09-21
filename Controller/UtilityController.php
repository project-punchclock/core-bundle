<?php
namespace Volleyball\Bundle\UtilityBundle\Controller;

use \Symfony\Component\HttpFoundation\Request;
use \Symfony\Component\DependencyInjection\ContainerInterface;
use \Symfony\Component\DependencyInjection\ContainerAwareInterface;
use \Symfony\Component\Config\FileLocator;
use \Symfony\Component\Yaml\Yaml;
use \Doctrine\Common\Collections\ArrayCollection;
use \FOS\RestBundle\View\View;
use \Sylius\Bundle\ResourceBundle\Controller\Configuration;

class UtilityController extends \Sylius\Bundle\ResourceBundle\Controller\ResourceController implements ContainerAwareInterface
{
    /**
     * configuration settings
     * @var ArrayCollection
     */
    private $configs;
    
    protected $config;

    /**
     * config file locator
     * @var FileLocater
     */
    private $locator;

    /**
     * container
     * @var type
     */
    protected $container;

    /**
     * security context
     * @var SecurityContext
     */
    protected $securityContext;
    
    /**
     * breadcrumb array
     * @var array 
     */
    protected $breadcrumbs = array();

    /**
     * set container
     * @param type $container container
     */
    public function setContainer(ContainerInterface $container = null)
    {
        parent::setContainer($container);
        
        $this->container = $container;

        $this->securityContext = $this->container->get('security.context');
        
        $this->generateBreadcrumbs();
    }

    /**
     * __construct
     */
    public function __construct(Configuration $config)
    {
        $path = __DIR__ .DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' .
                DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR .
                'app' . DIRECTORY_SEPARATOR;
        $configDirectories = array(
            __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'Resources' . DIRECTORY_SEPARATOR . 'config',
            $path . 'config'
        );
        $this->locator = new FileLocator($configDirectories);

        $this->config = $config;
    }

    public function getConfiguration()
    {
        return $this->config;
    }

    /**
     * Get configuration file parameter
     * @param string $parameter
     * @return type
     */
    public function getParameter($parameter)
    {
        if (!$this->configs instanceof ArrayCollection) {
            $this->generateConfigs('parameters.yml', null, false);
        }

        return $this->configs->get($parameter);
    }

    /**
     * Generate configuration array
     * @param string $file
     * @param string $path
     * @param bool $retFile
     */
    private function generateConfigs($file, $path = null, $retFile = false)
    {
        $configs = Yaml::parse($this->locator->locate($file, $path, $retFile)[0]);

        $this->configs = new ArrayCollection($configs['parameters']);
    }
    
    /**
     * Generate breadcrumbs
     */
    public function generateBreadcrumbs()
    {
        $this->breadcrumbs = $this->get('white_october_breadcrumbs');
        
        $this->breadcrumbs->addItem(
            'dashboard',
            $this->get('router')->generate('homepage')
        );
    }
    
    
    /**
     * @inheritdoc
     */
    public function indexAction(Request $request)
    {
        list($class, $tmp) = explode('Controller', class_name($this), 1);
        $this->breadcrumbs->add($this->get('volleyball.utility.inflector')->pluralize($class));
        
        return parent::indexAction($request);
    }
    
    /**
     * @inheritdoc
     */
    public function showAction(Request $request)
    {
        list($class, $tmp) = explode('Controller', class_name($this), 1);
        $this->breadcrumbs->add($this->get('volleyball.utility.inflector')->pluralize($class));
        $this->breadcrumbs->add('show');
        
        
        return parent::showAction($request);
    }
    
    /**
     * @inheritdoc
     */
    public function createAction(Request $request)
    {
        list($class, $tmp) = explode('Controller', class_name($this), 1);
        $this->breadcrumbs->add($this->get('volleyball.utility.inflector')->pluralize($class));
        $this->breadcrumbs->add('new');
        
        
        return parent::showAction($request);
    }
    
    /**
     * @inheritdoc
     */
    public function updateAction(Request $request)
    {
        list($class, $tmp) = explode('Controller', class_name($this), 1);
        $this->breadcrumbs->add($this->get('volleyball.utility.inflector')->pluralize($class));
        $this->breadcrumbs->add('update');
        
        
        return parent::showAction($request);
    }
    
    /**
     * @inheritdoc
     */
    public function deleteAction(Request $request)
    {
        list($class, $tmp) = explode('Controller', class_name($this), 1);
        $this->breadcrumbs->add($this->get('volleyball.utility.inflector')->pluralize($class));
        $this->breadcrumbs->add('delete');
        
        
        return parent::showAction($request);
    }
    
    /**
     * @return object
     */
    public function createNew()
    {
        return $this->resourceResolver->createResource($this->getRepository(), 'createNew');
    }

    /**
     * @param object|null $resource
     *
     * @return FormInterface
     */
    public function getForm($resource = null)
    {
        if ($this->config->isApiRequest()) {
            return $this->container->get('form.factory')->createNamed('', $this->config->getFormType(), $resource);
        }

        return $this->createForm($this->config->getFormType(), $resource);
    }

    /**
     * @param Request $request
     * @param array   $criteria
     *
     * @return object
     *
     * @throws NotFoundHttpException
     */
    public function findOr404(Request $request, array $criteria = array())
    {
        if ($request->get('slug')) {
            $default = array('slug' => $request->get('slug'));
        } elseif ($request->get('id')) {
            $default = array('id' => $request->get('id'));
        } else {
            $default = array();
        }

        $criteria = array_merge($default, $criteria);

        if (!$resource = $this->resourceResolver->getResource(
            $this->getRepository(),
            'findOneBy',
            array($this->config->getCriteria($criteria)))
        ) {
            throw new NotFoundHttpException(
                sprintf(
                    'Requested %s does not exist with these criteria: %s.',
                    $this->config->getResourceName(),
                    json_encode($this->config->getCriteria($criteria))
                )
            );
        }

        return $resource;
    }

    /**
     * @return RepositoryInterface
     */
    public function getRepository()
    {
        return $this->get($this->config->getServiceName('repository'));
    }

    /**
     * @param Request $request
     * @param integer $movement
     *
     * @return RedirectResponse
     */
    protected function move(Request $request, $movement)
    {
        $resource = $this->findOr404($request);

        $this->domainManager->move($resource, $movement);

        return $this->redirectHandler->redirectToIndex();
    }

    /**
     * @return PagerfantaFactory
     */
    protected function getPagerfantaFactory()
    {
        return new PagerfantaFactory('page', 'paginate');
    }

    protected function handleView(View $view)
    {
        $handler = $this->get('fos_rest.view_handler');
        $handler->setExclusionStrategyGroups($this->config->getSerializationGroups());

        if ($version = $this->config->getSerializationVersion()) {
            $handler->setExclusionStrategyVersion($version);
        }

        return $handler->handle($view);
    }
}
