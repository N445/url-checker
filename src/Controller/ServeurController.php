<?php

namespace App\Controller;

use App\Entity\Serveur;
use App\Form\ServeurType;
use App\Repository\ServeurRepository;
use App\Utils\NavPageGroup;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/serveur")
 */
class ServeurController extends AbstractController
{
    const PAGE_NAME = 'serveur';

    /**
     * @Route("/", name="SERVEUR_INDEX", methods={"GET"})
     */
    public function index(ServeurRepository $serveurRepository): Response
    {
        return $this->render('serveur/index.html.twig', [
            'serveurs'               => $serveurRepository->findAll(),
            NavPageGroup::PAGE_GROUP => self::PAGE_NAME,
        ]);
    }

    /**
     * @Route("/new", name="SERVEUR_NEW", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $serveur = new Serveur();
        $form    = $this->createForm(ServeurType::class, $serveur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($serveur);
            $entityManager->flush();

            return $this->redirectToRoute('SERVEUR_INDEX');
        }

        return $this->render('serveur/new.html.twig', [
            'serveur'                => $serveur,
            'form'                   => $form->createView(),
            NavPageGroup::PAGE_GROUP => self::PAGE_NAME,
        ]);
    }

    /**
     * @Route("/{id}", name="SERVEUR_SHOW", methods={"GET"})
     */
    public function show(Serveur $serveur): Response
    {
        return $this->render('serveur/show.html.twig', [
            'serveur'                => $serveur,
            NavPageGroup::PAGE_GROUP => self::PAGE_NAME,
        ]);
    }

    /**
     * @Route("/edit/{id}", name="SERVEUR_EDIT", methods={"GET","POST"})
     */
    public function edit(Request $request, Serveur $serveur): Response
    {
        $form = $this->createForm(ServeurType::class, $serveur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('SERVEUR_INDEX');
        }

        return $this->render('serveur/edit.html.twig', [
            'serveur'                => $serveur,
            'form'                   => $form->createView(),
            NavPageGroup::PAGE_GROUP => self::PAGE_NAME,
        ]);
    }

    /**
     * @Route("/{id}", name="SERVEUR_DELETE", methods={"DELETE"})
     */
    public function delete(Request $request, Serveur $serveur): Response
    {
        if ($this->isCsrfTokenValid('delete' . $serveur->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($serveur);
            $entityManager->flush();
        }

        return $this->redirectToRoute('SERVEUR_INDEX');
    }
}
