<?php

declare(strict_types=1);

namespace WebServCo\Configuration\Service;

use Override;
use UnexpectedValueException;
use WebServCo\Configuration\Contract\ConfigurationLoaderInterface;

use function is_array;

/**
 * Load configuration data from php file.
 *
 * Data must be an array.
 * Does not set the data, only retuns it.
 */
final class PHPConfigurationLoader extends AbstractConfigurationLoader implements ConfigurationLoaderInterface
{
    /**
     * Load configuration data from a file and return as array.
     *
     * @phpcs:disable SlevomatCodingStandard.TypeHints.DisallowMixedTypeHint.DisallowedMixedTypeHint
     * @return array<mixed>
     * @phpcs:enable
     */
    public function loadFromFile(string $filePath): array
    {
        $this->validateFilePath($filePath);
        /**
         * Load php file.
         *
         * @psalm-suppress UnresolvableInclude
         */
        $data = (require $filePath);
        if (!is_array($data)) {
            throw new UnexpectedValueException('Data is not an array.');
        }
        return $data;
    }
}
