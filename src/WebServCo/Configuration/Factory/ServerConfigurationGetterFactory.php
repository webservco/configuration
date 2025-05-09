<?php

declare(strict_types=1);

namespace WebServCo\Configuration\Factory;

use Override;
use WebServCo\Configuration\Contract\ConfigurationGetterFactoryInterface;
use WebServCo\Configuration\Contract\ConfigurationGetterInterface;
use WebServCo\Configuration\Service\ServerConfigurationGetter;

final class ServerConfigurationGetterFactory implements ConfigurationGetterFactoryInterface
{
    /**
     * Environment configuration.
     */
    public function createConfigurationGetter(): ConfigurationGetterInterface
    {
        return new ServerConfigurationGetter();
    }
}
