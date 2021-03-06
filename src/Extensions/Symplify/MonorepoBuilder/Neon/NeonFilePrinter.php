<?php

declare(strict_types=1);

namespace PoP\PoP\Extensions\Symplify\MonorepoBuilder\Neon;

use Symfony\Component\Console\Style\SymfonyStyle;
use Symplify\SmartFileSystem\SmartFileInfo;
use Symplify\SmartFileSystem\SmartFileSystem;

final class NeonFilePrinter
{
    public function __construct(
        private SmartFileSystem $smartFileSystem,
        private SymfonyStyle $symfonyStyle
    ) {
    }

    public function printContentToOutputFile(string $neonFileContent, string $outputFilePath): void
    {
        $this->smartFileSystem->dumpFile($outputFilePath, $neonFileContent);

        $outputFileInfo = new SmartFileInfo($outputFilePath);

        $message = sprintf('The monorepo PHPStan config file was created as "%s"', $outputFileInfo->getRelativeFilePathFromCwd());
        $this->symfonyStyle->success($message);

        $this->symfonyStyle->writeln('===================================');
        $this->symfonyStyle->newLine(1);
        $this->symfonyStyle->writeln('<comment>' . $neonFileContent . '</comment>');
        $this->symfonyStyle->writeln('===================================');
        $this->symfonyStyle->newLine(1);
    }
}
