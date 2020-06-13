<?php

namespace App\Command;

use App\Service\Checker\UrlChecker;
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
     * UrlCheckerCheckCommand constructor.
     * @param string|null $name
     * @param UrlChecker  $urlChecker
     */
    public function __construct(string $name = null, UrlChecker $urlChecker)
    {
        parent::__construct($name);
        $this->urlChecker = $urlChecker;
    }

    protected function configure()
    {
        $this->setDescription('Check les urls');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $this->urlChecker->run();

        $io->success('Ok.');

        return 0;
    }
}
