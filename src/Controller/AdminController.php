<?php

namespace App\Controller;

use App\Form\ImportType;
use App\Model\Import;
use App\Service\Import\Importator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AdminController
 * @package App\Controller
 * @Route("/")
 */
class AdminController extends AbstractController
{
    /**
     * @Route("/", name="ADMIN_DASHBOARD")
     */
    public function index(Request $request, Importator $importator)
    {
        $import = new Import();
        $form   = $this->createForm(ImportType::class, $import);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $importator->import($import);
        }
        return $this->render('admin/dashboard.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
