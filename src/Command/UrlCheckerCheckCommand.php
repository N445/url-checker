<?php

namespace App\Command;

use App\Service\Checker\UrlChecker;
use App\Service\Sender\RapportSender;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class UrlCheckerCheckCommand extends Command
{
    protected static $defaultName = 'app:url-checker:check';

    /**
     * @var UrlChecker
     */
    private $urlChecker;

    /**
     * @var RapportSender
     */
    private $rapportSender;

    /**
     * UrlCheckerCheckCommand constructor.
     * @param string|null   $name
     * @param UrlChecker    $urlChecker
     * @param RapportSender $rapportSender
     */
    public function __construct(string $name = null, UrlChecker $urlChecker, RapportSender $rapportSender)
    {
        parent::__construct($name);
        $this->urlChecker    = $urlChecker;
        $this->rapportSender = $rapportSender;
    }

    protected function configure()
    {
        $this->setDescription('Check les urls');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        dump((new \DateTime("NOW"))->format('H:i:s'));
        $this->urlChecker->run();
        $this->rapportSender->sendRapport();
        dump((new \DateTime("NOW"))->format('H:i:s'));

        $io->success('Ok.');

        return 0;
    }
}
