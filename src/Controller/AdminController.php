<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
    public function index()
    {
        return $this->render('admin/dashboard.html.twig', []);
    }
}
