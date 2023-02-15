<?php

declare(strict_types=1);

namespace WebServCo\Configuration\Contract;

interface ConfigurationSetterInterface extends ConfigurationServiceInterface
{
    public function append(string $key, bool|float|int|string|null $value): bool;

    public function set(string $key, bool|float|int|string|null $value): bool;
}
