<?php

declare(strict_types=1);

namespace WebServCo\Configuration;

use WebServCo\ConfigurationContract\ConfigurationSetterInterface;

/**
 * Configuration setter implementation using $_SERVER to store the data.
 */
final class ServerConfigurationSetter extends AbstractConfigurationService implements ConfigurationSetterInterface
{
    /**
     * Set a configuration option.
     *
     * `$keyPrefix` is appended to the key to avoid conflicts with existing data.
     *
     * @SuppressWarnings(PHPMD.Superglobals)
     */
    public function set(string $key, bool|float|int|string|null $value): bool
    {
        // AbstractConfigurationService
        $key = $this->processKey($key);

        // phpcs:ignore SlevomatCodingStandard.Variables.DisallowSuperGlobalVariable.DisallowedSuperGlobalVariable
        $_SERVER[$key] = $value;

        return true;
    }
}
