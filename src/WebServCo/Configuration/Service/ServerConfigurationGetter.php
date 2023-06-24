<?php

declare(strict_types=1);

namespace WebServCo\Configuration\Service;

use OutOfBoundsException;
use UnexpectedValueException;
use WebServCo\Configuration\Contract\ConfigurationGetterInterface;

use function array_key_exists;
use function is_array;
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
     * @phpcs:disable SlevomatCodingStandard.Variables.DisallowSuperGlobalVariable.DisallowedSuperGlobalVariable
     * @SuppressWarnings(PHPMD.Superglobals)
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
     * @SuppressWarnings(PHPMD.Superglobals)
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

        $this->validateArray($values);

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

    /**
     * @param mixed $data
     */
    private function validateArray($data): bool
    {
        if (!is_array($data)) {
            throw new UnexpectedValueException('Data is not an array.');
        }

        return true;
    }
}
