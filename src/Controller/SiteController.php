<?php

namespace App\Controller;

use App\Entity\Site;
use App\Form\SiteType;
use App\Repository\SiteRepository;
use App\Service\UrlHelper;
use App\Utils\NavPageGroup;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/site")
 */
class SiteController extends AbstractController
{
    const PAGE_NAME = 'site';

    /**
     * @var SiteRepository
     */
    private $siteRepository;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * SiteController constructor.
     * @param SiteRepository         $siteRepository
     * @param EntityManagerInterface $em
     */
    public function __construct(SiteRepository $siteRepository, EntityManagerInterface $em)
    {
        $this->siteRepository = $siteRepository;
        $this->em             = $em;
    }

    /**
     * @Route("/", name="SITE_INDEX", methods={"GET"})
     */
    public function index(): Response
    {
        return $this->render('site/index.html.twig', [
            'sites'                  => $this->siteRepository->findAll(),
            NavPageGroup::PAGE_GROUP => self::PAGE_NAME,
        ]);
    }

    /**
     * @Route("/new", name="SITE_NEW", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        $site = new Site();
        $form = $this->createForm(SiteType::class, $site);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($site);
            $this->em->flush();
            return $this->redirectToRoute('SITE_INDEX');
        }

        return $this->render('site/new.html.twig', [
            'site'                   => $site,
            'form'                   => $form->createView(),
            NavPageGroup::PAGE_GROUP => self::PAGE_NAME,
        ]);
    }

    /**
     * @Route("/{id}", name="SITE_SHOW", methods={"GET"})
     * @param Site $site
     * @return Response
     */
    public function show(Site $site): Response
    {
        return $this->render('site/show.html.twig', [
            'site'                   => $site,
            NavPageGroup::PAGE_GROUP => self::PAGE_NAME,
        ]);
    }

    /**
     * @Route("/edit/{id}", name="SITE_EDIT", methods={"GET","POST"})
     * @param Request   $request
     * @param int       $id
     * @param UrlHelper $urlHelper
     * @return Response
     */
    public function edit(Request $request, int $id, UrlHelper $urlHelper): Response
    {
        $site = $this->siteRepository->getSiteById($id);
        $form = $this->createForm(SiteType::class, $site);
        $urlHelper->setOldUrls($site);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $urlHelper->checkData($site);
            $this->em->persist($site);
            $this->em->flush();

//            return $this->redirectToRoute('SITE_INDEX');
        }

        return $this->render('site/edit.html.twig', [
            'site'                   => $site,
            'form'                   => $form->createView(),
            NavPageGroup::PAGE_GROUP => self::PAGE_NAME,
        ]);
    }

    /**
     * @Route("/{id}", name="SITE_DELETE", methods={"DELETE"})
     * @param Request $request
     * @param int     $id
     * @return Response
     */
    public function delete(Request $request, int $id): Response
    {
        $site = $this->siteRepository->getSiteById($id);
        if ($this->isCsrfTokenValid('delete' . $site->getId(), $request->request->get('_token'))) {
            $this->em->remove($site);
            $this->em->flush();
        }

        return $this->redirectToRoute('SITE_INDEX');
    }
}
