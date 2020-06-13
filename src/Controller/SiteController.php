<?php

namespace App\Controller;

use App\Entity\Site;
use App\Form\SiteType;
use App\Repository\SiteRepository;
use App\Utils\NavPageGroup;
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
     * @Route("/", name="SITE_INDEX", methods={"GET"})
     */
    public function index(SiteRepository $siteRepository): Response
    {
        return $this->render('site/index.html.twig', [
            'sites'                  => $siteRepository->findAll(),
            NavPageGroup::PAGE_GROUP => self::PAGE_NAME,
        ]);
    }

    /**
     * @Route("/new", name="SITE_NEW", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $site = new Site();
        $form = $this->createForm(SiteType::class, $site);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($site);
            $entityManager->flush();

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
     */
    public function edit(Request $request, Site $site): Response
    {
        $form = $this->createForm(SiteType::class, $site);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('SITE_INDEX');
        }

        return $this->render('site/edit.html.twig', [
            'site'                   => $site,
            'form'                   => $form->createView(),
            NavPageGroup::PAGE_GROUP => self::PAGE_NAME,
        ]);
    }

    /**
     * @Route("/{id}", name="SITE_DELETE", methods={"DELETE"})
     */
    public function delete(Request $request, Site $site): Response
    {
        if ($this->isCsrfTokenValid('delete' . $site->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($site);
            $entityManager->flush();
        }

        return $this->redirectToRoute('SITE_INDEX');
    }
}
