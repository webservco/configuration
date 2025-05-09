<?php

declare(strict_types=1);

namespace WebServCo\Configuration\Service;

use Override;
use UnexpectedValueException;
use WebServCo\Configuration\Contract\ConfigurationLoaderInterface;

use function is_array;
use function parse_ini_file;

use const INI_SCANNER_TYPED;

/**
 * Load configuration data from ini file.
 */
final class IniConfigurationLoader extends AbstractConfigurationLoader implements ConfigurationLoaderInterface
{
    /**
     * Load configuration data from a file and return as array.
     *
     * @phpcs:disable SlevomatCodingStandard.TypeHints.DisallowMixedTypeHint.DisallowedMixedTypeHint
     * @return array<mixed>
     * @phpcs:enable
     */
    #[Override]
    public function loadFromFile(string $filePath): array
    {
        $this->validateFilePath($filePath);

        $data = parse_ini_file(
            $filePath,
            // process_sections
            false,
            // scanner_mode: \INI_SCANNER_TYPED - "boolean, null and integer types are preserved when possible"
            INI_SCANNER_TYPED,
        );

        // Check array

        if (!is_array($data)) {
            throw new UnexpectedValueException('Configuration data is not an array.');
        }

        // Rest of the data will be validated by the consumer method.

        return $data;
    }
}
