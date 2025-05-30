<?php

declare(strict_types=1);

namespace WebServCo\Configuration\Service;

use Override;
use UnexpectedValueException;
use WebServCo\Configuration\Contract\ConfigurationServiceInterface;

use function gettype;
use function is_array;
use function is_scalar;
use function mb_strtoupper;
use function sprintf;

abstract class AbstractConfigurationService implements ConfigurationServiceInterface
{
    public function __construct(protected string $keyPrefix = 'APP_')
    {
    }

    #[Override]
    public function getValidatedScalarValue(mixed $value): bool|float|int|string|null
    {
        if (!is_scalar($value) && $value !== null) {
            throw new UnexpectedValueException(sprintf('Invalid configuration value type: "%s".', gettype($value)));
        }

        return $value;
    }

    /**
     * @return array<bool|float|int|string|null>|bool|float|int|string|null
     */
    #[Override]
    public function getValidatedValue(mixed $value): array|bool|float|int|string|null
    {
        if (is_array($value)) {
            $result = [];
            /**
             * Psalm error: "Unable to determine the type that $.. is being assigned to"
             * However this is indeed mixed, no solution but to suppress error.
             *
             * @psalm-suppress MixedAssignment
             */
            foreach ($value as $item) {
                $result[] = $this->getValidatedScalarValue($item);
            }

            return $result;
        }

        return $this->getValidatedScalarValue($value);
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
