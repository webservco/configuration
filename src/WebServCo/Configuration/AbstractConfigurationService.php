<?php

declare(strict_types=1);

namespace WebServCo\Configuration;

use UnexpectedValueException;
use WebServCo\ConfigurationContract\ConfigurationServiceInterface;

use function gettype;
use function is_scalar;
use function mb_strtoupper;
use function sprintf;

abstract class AbstractConfigurationService implements ConfigurationServiceInterface
{
    public function __construct(protected string $keyPrefix = 'APP_')
    {
    }

    public function validateValue(mixed $value): bool
    {
        if (!is_scalar($value) && $value !== null) {
            throw new UnexpectedValueException(sprintf('Invalid configuration value type: "%s".', gettype($value)));
        }

        return true;
    }

    /**
     * Process key.
     *
     * Adds prefix and transforms to uppercase.
     */
    protected function processKey(string $key): string
    {
        return mb_strtoupper($this->keyPrefix . $key);
    }
}
