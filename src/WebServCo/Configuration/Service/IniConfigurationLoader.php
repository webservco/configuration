<?php

declare(strict_types=1);

namespace WebServCo\Configuration\Service;

use UnexpectedValueException;
use WebServCo\Configuration\Contract\ConfigurationLoaderInterface;
use WebServCo\Configuration\Contract\ConfigurationProcessorInterface;
use WebServCo\Configuration\Contract\ConfigurationSetterInterface;

use function is_array;
use function is_string;
use function parse_ini_file;

use const INI_SCANNER_TYPED;

/**
 * Load configuration data from ini file.
 *
 * Set the data using a configuration setter.
 */
final class IniConfigurationLoader extends AbstractConfigurationLoader implements
    ConfigurationLoaderInterface,
    ConfigurationProcessorInterface
{
    public function __construct(private ConfigurationSetterInterface $configurationSetter)
    {
    }

    /**
     * Load configuration data from a file and return as array.
     *
     * phpcs:disable SlevomatCodingStandard.TypeHints.DisallowMixedTypeHint.DisallowedMixedTypeHint
     * @return array<mixed>
     * phpcs:enable
     */
    public function loadFromFile(string $filePath): array
    {
        $this->validateFilePath($filePath);

        /**
         * Detailed type for static analysis, not actually specified by `parse_ini_file`.
         *
         * phpcs:disable SlevomatCodingStandard.TypeHints.DisallowMixedTypeHint.DisallowedMixedTypeHint
         * @var bool|array<mixed> $data
         * phpcs: enable
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
     * Process configuration data
     *
     * phpcs:disable SlevomatCodingStandard.TypeHints.DisallowMixedTypeHint.DisallowedMixedTypeHint
     * @param array<mixed> $data
     * phpcs:enable
     */
    public function process(array $data): bool
    {
        /**
         * Psalm error: "Unable to determine the type that $.. is being assigned to"
         * However this is indeed mixed, no solution but to supress error.
         *
         * @psalm-suppress MixedAssignment
         */
        foreach ($data as $key => $value) {
            // This needs to be here and not in separate method, for PHPStan..
            if (!is_string($key)) {
                throw new UnexpectedValueException('Configuration key is not a string.');
            }

            $this->processValue($key, $value);
        }

        return true;
    }

    /**
     * Process individual configuration value.
     */
    private function processValue(string $key, mixed $value): bool
    {
        if (is_array($value)) {
            /**
             * Psalm error: "Unable to determine the type that $.. is being assigned to"
             * However this is indeed mixed, no solution but to supress error.
             *
             * @psalm-suppress MixedAssignment
             */
            foreach ($value as $individualValue) {
                $individualValue = $this->configurationSetter->getValidatedScalarValue($individualValue);
                $this->configurationSetter->append($key, $individualValue);
            }

            return true;
        }
        // Not array.

        $value = $this->configurationSetter->getValidatedScalarValue($value);
        $this->configurationSetter->set($key, $value);

        return true;
    }
}
