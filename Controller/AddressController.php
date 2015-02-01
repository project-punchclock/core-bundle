<?php
namespace ProjectPunchclock\Bundle\CoreBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\DoctrineORMAdapter;

use ProjectPunchclock\Bundle\CoreBundle\Entity\Address;
use ProjectPunchclock\Bundle\CoreBundle\Form\Type\AddressType;

/**
 * @Route("/addresses")
 */
class AddressController extends CoreController
{
    /**
     * @Route("/", name="project_punchclock_address_index")
     * @Template("ProjectPunchclockCoreBundle:Address:index.html.twig")
     */
    public function indexAction(Request $request)
    {
        // get route name/params to decypher data to delimit by
        $query = $this->get('doctrine')
            ->getRepository('ProjectPunchclockCoreBundle:Address')
            ->createQueryBuilder('l')
            ->orderBy('l.updated, l.name', 'ASC');

        $pager = new Pagerfanta(new DoctrineORMAdapter($query));
        $pager->setMaxPerPage($this->getRequest()->get('pageMax', 5));
        $pager->setCurrentPage($this->getRequest()->get('page', 1));

        return array(
          'addresss' => $pager->getCurrentPageResults(),
          'pager'  => $pager
        );
    }

    /**
     * @Route("/{slug}", name="project_punchclock_address_show")
     * @Template("ProjectPunchclockCoreBundle:Address:show.html.twig")
     */
    public function showAction(Request $request)
    {
        $slug = $request->getParameter('slug');
        $address = $this->getDoctrine()
            ->getRepository('ProjectPunchclockCoreBundle:Address')
            ->findOneBySlug($slug);

        if (!$address) {
            $this->get('session')
                ->getFlashBag()->add(
                    'error',
                    'no matching address found.'
                );
            $this->redirect($this->generateUrl('project_punchclock_address_index'));
        }

        return array('address' => $address);
    }

    /**
     * @Route("/new", name="project_punchclock_address_new")
     * @Template("ProjectPunchclockCoreBundle:Address:new.html.twig")
     */
    public function newAction(Request $request)
    {
        $address = new Address();
        $form = $this->createForm(new AddressType(), $address);

        if ("POST" == $request->getMethod()) {
            $form->handleRequest($this->getRequest());
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getEntityManager();
                $em->persist($address);
                $em->flush();

                $this->get('session')->getFlashBag()->add(
                    'success',
                    'address created.'
                );

                return $this->render(
                    'ProjectPunchclockCoreBundle:Address:show.html.twig',
                    array(
                        'address' => $address
                    )
                );
            }
        }

        return array('form' => $form->createView());
    }
}
