<?php

declare(strict_types=1);

namespace WebServCo\Configuration\Contract;

interface ConfigurationServiceInterface
{
    public function getValidatedScalarValue(mixed $value): bool|float|int|string|null;

    /**
     * @return array<bool|float|int|string|null>|bool|float|int|string|null
     */
    public function getValidatedValue(mixed $value): array|bool|float|int|string|null;
}
