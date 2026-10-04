<?php

declare(strict_types=1);

namespace WebServCo\Configuration\Contract;

interface ConfigurationSetterInterface extends ConfigurationServiceInterface
{
    /**
     * @param bool|float|int|string|null $value
     */
    public function append(string $key, $value): bool;

    /**
     * @param bool|float|int|string|null $value
     */
    public function set(string $key, $value): bool;
}
