<?php

declare(strict_types=1);

namespace PoP\PoP\Config\Symplify\MonorepoBuilder;

class DowngradeRectorConfig
{
    function __construct(protected string $dir)
    {        
    }

    /**
     * @return string[]
     */
    public function getAdditionalDowngradeRectorConfigFiles(): array
    {
        return [
            $this->dir . '/ci/downgrades/rector-downgrade-code-hacks-CacheItem.php',
            $this->dir . '/ci/downgrades/rector-downgrade-code-hacks-ArrowFnMixedType.php',
            $this->dir . '/ci/downgrades/rector-downgrade-code-hacks-ArrowFnUnionType.php',
        ];
    }
}
