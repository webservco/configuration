<?php

declare(strict_types=1);

namespace WebServCo\Configuration\Service;

use OutOfBoundsException;
use Override;
use UnexpectedValueException;
use WebServCo\Configuration\Contract\ConfigurationGetterInterface;

use function array_key_exists;
use function is_bool;
use function is_float;
use function is_int;
use function is_string;
use function sprintf;

/**
 * Configuration getter implementation using $_SERVER to store the data.
 */
final class ServerConfigurationGetter extends AbstractConfigurationService implements ConfigurationGetterInterface
{
    /**
     * @var string
     */
    private const MESSAGE_VALUE_TYPE_DIFFERENT = 'Data type is different than expected.';

    /**
     * @see \WebServCo\Configuration\Interface\ConfigurationGetterInterface for method description.
     * @phpcs:disable SlevomatCodingStandard.Variables.DisallowSuperGlobalVariable.DisallowedSuperGlobalVariable
     * @SuppressWarnings("PHPMD.Superglobals")
     * @return bool|float|int|string|null
     */
    public function get(string $key)
    {
        $key = $this->processKey($key);
        if (!array_key_exists($key, $_SERVER)) {
            throw new OutOfBoundsException(sprintf('Configuration key "%s" does not exist.', $key));
        }
        return $this->getValidatedScalarValue($_SERVER[$key]);
    }
    //@phpcs:enable

    /**
     * Return a list of values.
     *
     * @phpcs:disable SlevomatCodingStandard.Variables.DisallowSuperGlobalVariable.DisallowedSuperGlobalVariable
     * @SuppressWarnings("PHPMD.Superglobals")
     * @return array<int,bool|float|int|string|null>
     */
    public function getArray(string $key): array
    {
        $key = $this->processKey($key);
        if (!array_key_exists($key, $_SERVER)) {
            throw new OutOfBoundsException(sprintf('Configuration key "%s" does not exist.', $key));
        }
        /**
         * Docblock for static analysis.
         *
         * @var array<int,bool|float|int|string|null> $values
         */
        $values = $_SERVER[$key];
        $result = [];
        foreach ($values as $value) {
            $result[] = $this->getValidatedScalarValue($value);
        }
        return $result;
    }
    // @phpcs:enable

    public function getBool(string $key): bool
    {
        $value = $this->get($key);
        if (!is_bool($value)) {
            throw new UnexpectedValueException(self::MESSAGE_VALUE_TYPE_DIFFERENT);
        }
        return $value;
    }

    public function getInt(string $key): int
    {
        $value = $this->get($key);
        if (!is_int($value)) {
            throw new UnexpectedValueException(self::MESSAGE_VALUE_TYPE_DIFFERENT);
        }
        return $value;
    }

    public function getFloat(string $key): float
    {
        $value = $this->get($key);
        if (!is_float($value)) {
            throw new UnexpectedValueException(self::MESSAGE_VALUE_TYPE_DIFFERENT);
        }
        return $value;
    }

    public function getString(string $key): string
    {
        $value = $this->get($key);
        if (!is_string($value)) {
            throw new UnexpectedValueException(self::MESSAGE_VALUE_TYPE_DIFFERENT);
        }
        return $value;
    }
}
