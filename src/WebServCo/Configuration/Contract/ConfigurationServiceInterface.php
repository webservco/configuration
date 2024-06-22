<?php

declare(strict_types=1);

namespace WebServCo\Configuration\Contract;

interface ConfigurationServiceInterface
{
    /**
     * @return bool|float|int|string|null
     * @param mixed $value
     */
    public function getValidatedScalarValue($value);

    /**
     * @return array<bool|float|int|string|null>|bool|float|int|string|null
     * @param mixed $value
     */
    public function getValidatedValue($value);
}
