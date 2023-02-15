<?php

declare(strict_types=1);

namespace WebServCo\Configuration\Contract;

interface ConfigurationContainerInterface
{
    public function getConfigurationDataProcessor(): ConfigurationDataProcessorInterface;

    public function getConfigurationLoader(): ConfigurationLoaderInterface;

    public function getConfigurationSetter(): ConfigurationSetterInterface;
}
