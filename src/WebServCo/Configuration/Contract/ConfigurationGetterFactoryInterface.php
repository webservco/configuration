<?php

declare(strict_types=1);

namespace WebServCo\Configuration\Contract;

interface ConfigurationGetterFactoryInterface
{
    public function createConfigurationGetter(): ConfigurationGetterInterface;
}
