<?php

declare(strict_types=1);

namespace PoP\PoP\Extensions\Symplify\MonorepoBuilder\Command;

use Nette\Utils\Json;
use PoP\PoP\Extensions\Symplify\MonorepoBuilder\Json\SourcePackagesProvider;
use PoP\PoP\Extensions\Symplify\MonorepoBuilder\ValueObject\Option;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symplify\PackageBuilder\Console\Command\AbstractSymplifyCommand;
use Symplify\PackageBuilder\Console\ShellCode;

final class SourcePackagesCommand extends AbstractSymplifyCommand
{
    private SourcePackagesProvider $sourcePackagesProvider;

    public function __construct(SourcePackagesProvider $sourcePackagesProvider)
    {
        $this->sourcePackagesProvider = $sourcePackagesProvider;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setDescription('Provides source packages (i.e. packages with code under src/ and tests/), in json format. Useful for GitHub Actions Workflow');
        $this->addOption(
            Option::JSON,
            null,
            InputOption::VALUE_NONE,
            'Print with encoded JSON format.'
        );
        $this->addOption(
            Option::PSR4_ONLY,
            null,
            InputOption::VALUE_NONE,
            'Skip the non-PSR-4 packages.'
        );
        $this->addOption(
            Option::SKIP_UNMIGRATED,
            null,
            InputOption::VALUE_NONE,
            'Skip the not-yet-migrated to PSR-4 packages.'
        );
        $this->addOption(
            Option::SUBFOLDER,
            null,
            InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY,
            'Add paths to a subfolder from the package.',
            []
        );
        $this->addOption(
            Option::FILTER,
            null,
            InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY,
            'Filter the packages to those from the list of files. Useful to split monorepo on modified packages only',
            []
        );
        $this->addOption(
            Option::FILTER_LIST,
            null,
            InputOption::VALUE_REQUIRED,
            'Space-separate list of files to filter the packages by. Useful to split monorepo on modified packages only',
            ''
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $asJSON = (bool) $input->getOption(Option::JSON);
        $psr4Only = (bool) $input->getOption(Option::PSR4_ONLY);
        $skipUnmigrated = (bool) $input->getOption(Option::SKIP_UNMIGRATED);
        /** @var string[] $subfolders */
        $subfolders = $input->getOption(Option::SUBFOLDER);

        // 2 ways to filter packages:
        // - many single files doing --filter: --filter=file1 --filter=file2
        // - many files together doing --filter-list: --filter-list="file1 file2"
        /** @var string[] $fileFilter */
        $fileFilter = $input->getOption(Option::FILTER);
        /** @var string $fileListFilter */
        $fileListFilter = $input->getOption(Option::FILTER_LIST);
        if ($fileListFilter !== '') {
            $fileFilter = array_merge(
                $fileFilter,
                explode(' ', $fileListFilter)
            );
        }

        $sourcePackages = $this->sourcePackagesProvider->provideSourcePackages($psr4Only, $skipUnmigrated, $fileFilter);

        // Point to some subfolder?
        if ($subfolders !== []) {
            $sourcePackagePaths = [];
            foreach ($sourcePackages as $sourcePackage) {
                foreach ($subfolders as $subfolder) {
                    $sourcePackagePaths[] = $sourcePackage . DIRECTORY_SEPARATOR . $subfolder;
                }
            }
        } else {
            $sourcePackagePaths = $sourcePackages;
        }

        // JSON: must be without spaces, otherwise it breaks GitHub Actions json
        $response = $asJSON ? Json::encode($sourcePackagePaths) : implode(' ', $sourcePackagePaths);
        $this->symfonyStyle->writeln($response);

        return ShellCode::SUCCESS;
    }
}
