<?php

declare(strict_types=1);

namespace PoP\APIMirrorQuery\DataStructureFormatters;

use PoP\ComponentModel\DataStructure\PropertyDataStructureFormatterTrait;

class PropertyMirrorQueryDataStructureFormatter extends MirrorQueryDataStructureFormatter
{
    use PropertyDataStructureFormatterTrait;

    public function getName(): string
    {
        return 'props';
    }
}
