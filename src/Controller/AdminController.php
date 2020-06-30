<?php

namespace App\Controller;

use App\Entity\Url;
use App\Form\ImportType;
use App\Model\Import;
use App\Repository\UrlRepository;
use App\Service\Import\Importator;
use GuzzleHttp\Client;
use League\Csv\Writer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
     * @param Request    $request
     * @param Importator $importator
     * @return Response
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

    /**
     * Création de la route "export"
     * @Route("/export", name="ADMIN_EXPORT", methods={"GET"})
     * @param UrlRepository $urlRepository
     * @return void
     * @throws \League\Csv\CannotInsertRecord
     */
    public function export(UrlRepository $urlRepository)
    {
        $writer = Writer::createFromFileObject(new \SplTempFileObject());

        $writer->insertOne([Importator::SERVEUR, Importator::PROTOCOL, Importator::SITE, Importator::URL, Importator::CODE]);

        $writer->insertAll(array_map(function (Url $url) {
            return [
                $url->getSite()->getServeur()->getIp(),
                $url->getSite()->getProtocol(),
                $url->getSite()->getDomain(),
                $url->getUrl(),
                $url->getCode(),
            ];
        }, $urlRepository->findAll()));
        $writer->output('urls.csv');
        die;
    }

    /**
     * Création de la route "test"
     * @Route("/test", name="TEST", methods={"GET"})
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function test(Request $request)
    {
        return $this->render('admin/test.html.twig', []);
    }


}
