<?php

declare(strict_types=1);

namespace WebServCo\Configuration\Contract;

interface ConfigurationGetterInterface extends ConfigurationServiceInterface
{
    /**
     * Get the value of a configuration option from storage.
     *
     * Key is processed / validated, and should be used without the prefix.
     * Return types are the ones supported by the setter.
    */
    public function get(string $key): bool|float|int|string|null;

    /**
     * Return a list of values.
     *
     * @return array<int,bool|float|int|string|null>
     */
    public function getArray(string $key): array;

    public function getBool(string $key): bool;

    public function getString(string $key): string;
}
