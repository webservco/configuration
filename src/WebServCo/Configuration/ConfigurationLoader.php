<?php

declare(strict_types=1);

namespace WebServCo\Configuration;

use RuntimeException;
use UnexpectedValueException;
use WebServCo\ConfigurationContract\ConfigurationSetterInterface;

use function is_array;
use function is_readable;
use function is_string;
use function parse_ini_file;

use const INI_SCANNER_TYPED;

/**
 * Load configuration data from file.
 *
 * Set the data using a configuration setter.
 */
final class ConfigurationLoader
{
    public function __construct(private ConfigurationSetterInterface $configurationSetter)
    {
    }

    /**
     * Load configuration data from a file as environment variables.
     */
    public function loadFromFile(string $filePath): bool
    {
        $this->validateFilePath($filePath);

        /**
         * Get an array of data from the configuration file.
         *
         * @var array<int|string,bool|float|int|string|null>
         */
        $data = parse_ini_file(
            $filePath,
            // process_sections
            false,
            // scanner_mode: \INI_SCANNER_TYPED - "boolean, null and integer types are preserved when possible"
            INI_SCANNER_TYPED,
        );

        $this->validateData($data);

        foreach ($data as $key => $value) {
            if (!is_string($key)) {
                throw new UnexpectedValueException('Configuration key is not a string.');
            }

            $this->configurationSetter->validateValue($value);
            $this->configurationSetter->set($key, $value);
        }

        return true;
    }

    /**
     * Simple data validation.
     *
     * @param bool|array<int|string,bool|float|int|string|null> $data
     */
    private function validateData(bool|array $data): bool
    {
        if (!is_array($data)) {
            throw new UnexpectedValueException('Configuration data is not an array.');
        }

        return true;
    }

    private function validateFilePath(string $filePath): bool
    {
        if (!is_readable($filePath)) {
            /**
             * Since environment is not set at this point,
             * it will usually not be possible to show separate messages, or use logging,
             * so for security reasons do not add the actual file path in the error message.
             */
            throw new RuntimeException('Configuration file path not readable.');
        }

        return true;
    }
}
