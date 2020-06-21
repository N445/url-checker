<?php

namespace App\Controller;

use App\Entity\Serveur;
use App\Form\ServeurType;
use App\Repository\ServeurRepository;
use App\Utils\NavPageGroup;
use Doctrine\ORM\EntityManagerInterface;
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
     * @var ServeurRepository
     */
    private $serveurRepository;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * ServeurController constructor.
     * @param ServeurRepository      $serveurRepository
     * @param EntityManagerInterface $em
     */
    public function __construct(ServeurRepository $serveurRepository, EntityManagerInterface $em)
    {
        $this->serveurRepository = $serveurRepository;
        $this->em                = $em;
    }

    /**
     * @Route("/", name="SERVEUR_INDEX", methods={"GET"})
     */
    public function index(): Response
    {
        return $this->render('serveur/index.html.twig', [
            'serveurs'               => $this->serveurRepository->getServers(),
            NavPageGroup::PAGE_GROUP => self::PAGE_NAME,
        ]);
    }

    /**
     * @Route("/new", name="SERVEUR_NEW", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        $serveur = new Serveur();
        $form    = $this->createForm(ServeurType::class, $serveur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($serveur);
            $this->em->flush();

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
     * @param int $id
     * @return Response
     */
    public function show(int $id): Response
    {
        return $this->render('serveur/show.html.twig', [
            'serveur'                => $this->serveurRepository->getServer($id),
            NavPageGroup::PAGE_GROUP => self::PAGE_NAME,
        ]);
    }

    /**
     * @Route("/edit/{id}", name="SERVEUR_EDIT", methods={"GET","POST"})
     * @param Request $request
     * @param int     $id
     * @return Response
     */
    public function edit(Request $request, int $id): Response
    {
        $serveur = $this->serveurRepository->getServer($id);
        $form    = $this->createForm(ServeurType::class, $serveur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->flush();

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
     * @param Request $request
     * @param int     $id
     * @return Response
     */
    public function delete(Request $request, int $id): Response
    {
        $serveur = $this->serveurRepository->getServer($id);
        if ($this->isCsrfTokenValid('delete' . $serveur->getId(), $request->request->get('_token'))) {
            $this->em->remove($serveur);
            $this->em->flush();
        }

        return $this->redirectToRoute('SERVEUR_INDEX');
    }
}
