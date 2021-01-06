<?php

declare(strict_types=1);

namespace PoP\PoP\Extensions\Symplify\MonorepoBuilder\Command;

use Nette\Utils\Json;
use PoP\PoP\Extensions\Symplify\MonorepoBuilder\Json\SourcePackageEntriesJsonProvider;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symplify\PackageBuilder\Console\Command\AbstractSymplifyCommand;
use Symplify\PackageBuilder\Console\ShellCode;

final class SourcePackageEntriesJsonCommand extends AbstractSymplifyCommand
{
    /**
     * @var SourcePackageEntriesJsonProvider
     */
    private $sourcePackageEntriesJsonProvider;

    public function __construct(SourcePackageEntriesJsonProvider $sourcePackageEntriesJsonProvider)
    {
        $this->sourcePackageEntriesJsonProvider = $sourcePackageEntriesJsonProvider;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setDescription('Provides source packages (i.e. packages with code under src/ and tests/), in json format. Useful for GitHub Actions Workflow');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $sourcePackageEntries = $this->sourcePackageEntriesJsonProvider->provideSourcePackageEntries();

        // must be without spaces, otherwise it breaks GitHub Actions json
        $json = Json::encode($sourcePackageEntries);
        $this->symfonyStyle->writeln($json);

        return ShellCode::SUCCESS;
    }
}
