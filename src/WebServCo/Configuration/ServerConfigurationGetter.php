<?php

declare(strict_types=1);

namespace WebServCo\Configuration;

use OutOfBoundsException;
use UnexpectedValueException;
use WebServCo\ConfigurationContract\ConfigurationGetterInterface;

use function array_key_exists;
use function is_bool;
use function is_string;
use function sprintf;

/**
 * Configuration getter implementation using $_SERVER to store the data.
 */
final class ServerConfigurationGetter extends AbstractConfigurationService implements ConfigurationGetterInterface
{
    /**
     * @see \WebServCo\Configuration\Interface\ConfigurationGetterInterface for method description.
     * @SuppressWarnings(PHPMD.Superglobals)
     */
    public function get(string $key): bool|float|int|string|null
    {
        $key = $this->processKey($key);

        // phpcs:ignore SlevomatCodingStandard.Variables.DisallowSuperGlobalVariable.DisallowedSuperGlobalVariable
        if (!array_key_exists($key, $_SERVER)) {
            throw new OutOfBoundsException(sprintf('Configuration key "%s" does not exist.', $key));
        }

        /**
         * Docblock for static analysis.
         *
         * @var bool|float|int|string|null
         */
        // phpcs:ignore SlevomatCodingStandard.Variables.DisallowSuperGlobalVariable.DisallowedSuperGlobalVariable
        $value = $_SERVER[$key];

        $this->validateValue($value);

        return $value;
    }

    public function getBool(string $key): bool
    {
        $value = $this->get($key);
        if (!is_bool($value)) {
            throw new UnexpectedValueException(sprintf('Value for configuration key "%s" is not boolean.', $key));
        }

        return $value;
    }

    public function getString(string $key): string
    {
        $value = $this->get($key);
        if (!is_string($value)) {
            throw new UnexpectedValueException(sprintf('Value for configuration key "%s" is not string.', $key));
        }

        return $value;
    }
}
