<?php

declare(strict_types=1);

namespace WebServCo\Configuration\Service;

use UnexpectedValueException;
use WebServCo\Configuration\Contract\ConfigurationDataProcessorInterface;
use WebServCo\Configuration\Contract\ConfigurationSetterInterface;

use function is_array;
use function is_string;

/**
 * Set the data loaded by a loader using a configuration setter.
 */
final class ConfigurationDataProcessor extends AbstractConfigurationLoader implements
    ConfigurationDataProcessorInterface
{
    public function __construct(private ConfigurationSetterInterface $configurationSetter)
    {
    }

    /**
     * Process configuration data
     *
     * @phpcs:disable SlevomatCodingStandard.TypeHints.DisallowMixedTypeHint.DisallowedMixedTypeHint
     * @param array<mixed> $data
     * @phpcs:enable
     */
    public function process(array $data): bool
    {
        /**
         * Psalm error: "Unable to determine the type that $.. is being assigned to"
         * However this is indeed mixed, no solution but to spupress error.
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
             * However this is indeed mixed, no solution but to suppress error.
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
