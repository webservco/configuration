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
    private ConfigurationSetterInterface $configurationSetter;
    public function __construct(ConfigurationSetterInterface $configurationSetter)
    {
        $this->configurationSetter = $configurationSetter;
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
     * @param mixed $value
     */
    private function processValue(string $key, $value): bool
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
