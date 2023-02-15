<?php

declare(strict_types=1);

namespace WebServCo\Configuration\Contract;

interface ConfigurationLoaderInterface
{
    /**
     * Load configuration data from a file and return as array.
     *
     * phpcs:disable SlevomatCodingStandard.TypeHints.DisallowMixedTypeHint.DisallowedMixedTypeHint
     * @return array<mixed>
     * phpcs:enable
     */
    public function loadFromFile(string $filePath): array;
}
