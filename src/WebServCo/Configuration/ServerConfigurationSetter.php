<?php

declare(strict_types=1);

namespace WebServCo\Configuration;

use RuntimeException;
use WebServCo\ConfigurationContract\ConfigurationSetterInterface;

use function array_key_exists;
use function is_array;

/**
 * Configuration setter implementation using $_SERVER to store the data.
 */
final class ServerConfigurationSetter extends AbstractConfigurationService implements ConfigurationSetterInterface
{
    /**
     * Append a configuration option to a (n optionally already existing) list containing multiple values.
     *
     * `$keyPrefix` is appended to the key to avoid conflicts with existing data.
     * phpcs:disable SlevomatCodingStandard.Variables.DisallowSuperGlobalVariable.DisallowedSuperGlobalVariable
     * @SuppressWarnings(PHPMD.Superglobals)
     */
    public function append(string $key, bool|float|int|string|null $value): bool
    {
        // AbstractConfigurationService
        $key = $this->processKey($key);

        // Create list (array) if not exists.
        if (!array_key_exists($key, $_SERVER)) {
            $_SERVER[$key] = [];
        }

        if (!is_array($_SERVER[$key])) {
            throw new RuntimeException('Data is not an array.');
        }

        $_SERVER[$key][] = $value;

        return true;
    }
    // phpcs: enable

    /**
     * Set a configuration option.
     *
     * `$keyPrefix` is appended to the key to avoid conflicts with existing data.
     *
     * phpcs:disable SlevomatCodingStandard.Variables.DisallowSuperGlobalVariable.DisallowedSuperGlobalVariable
     * @SuppressWarnings(PHPMD.Superglobals)
     */
    public function set(string $key, bool|float|int|string|null $value): bool
    {
        // AbstractConfigurationService
        $key = $this->processKey($key);

        $_SERVER[$key] = $value;

        return true;
    }
    // phpcs: enable
}
