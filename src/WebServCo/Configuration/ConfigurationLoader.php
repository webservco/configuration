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

        $data = $this->parseIniFile($filePath);

        foreach ($data as $key => $value) {
            // This needs to be here and not in separate method, for PHPStan..
            if (!is_string($key)) {
                throw new UnexpectedValueException('Configuration key is not a string.');
            }

            $this->parseValue($key, $value);
        }

        return true;
    }

    /**
     * Get an array of data from the configuration file.
     *
     * The detailed return type specified in docblock is for static analysis and will have to be furhter validated.
     * (`parse_ini_file` does not specify the detailed return type)
     *
     * @return array<int|string,array<bool|float|int|string|null>|bool|float|int|string|null>
     */
    private function parseIniFile(string $filePath): array
    {
        /**
         * Detailed type for static analysis, not actually specified by `parse_ini_file`.
         *
         * @var bool|array<int|string,array<bool|float|int|string|null>|bool|float|int|string|null> $data
         */
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

    /**
     * Parse individual configuration value.
     *
     * @param array<bool|float|int|string|null>|bool|float|int|string|null $value
     */
    private function parseValue(string $key, array|bool|float|int|string|null $value): bool
    {
        if (is_array($value)) {
            foreach ($value as $individualValue) {
                $this->configurationSetter->validateValue($individualValue);
                $this->configurationSetter->append($key, $individualValue);
            }

            return true;
        }
        // Not array.

        $this->configurationSetter->validateValue($value);
        $this->configurationSetter->set($key, $value);

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
