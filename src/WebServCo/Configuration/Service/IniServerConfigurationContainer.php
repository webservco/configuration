<?php

declare(strict_types=1);

namespace WebServCo\Configuration\Service;

use Override;
use WebServCo\Configuration\Contract\ConfigurationContainerInterface;
use WebServCo\Configuration\Contract\ConfigurationDataProcessorInterface;
use WebServCo\Configuration\Contract\ConfigurationLoaderInterface;
use WebServCo\Configuration\Contract\ConfigurationSetterInterface;

/**
 * A configuration container using an "ini" loader and a "$_SERVER" setter.
 */
final class IniServerConfigurationContainer implements ConfigurationContainerInterface
{
    private ?ConfigurationDataProcessorInterface $configurationDataProcessor = null;
    private ?ConfigurationLoaderInterface $configurationLoader = null;
    private ?ConfigurationSetterInterface $configurationSetter = null;

    public function getConfigurationDataProcessor(): ConfigurationDataProcessorInterface
    {
        if ($this->configurationDataProcessor === null) {
            $this->configurationDataProcessor = new ConfigurationDataProcessor($this->getConfigurationSetter());
        }
        return $this->configurationDataProcessor;
    }

    public function getConfigurationLoader(): ConfigurationLoaderInterface
    {
        if ($this->configurationLoader === null) {
            $this->configurationLoader = new IniConfigurationLoader();
        }
        return $this->configurationLoader;
    }

    public function getConfigurationSetter(): ConfigurationSetterInterface
    {
        if ($this->configurationSetter === null) {
            $this->configurationSetter = new ServerConfigurationSetter();
        }
        return $this->configurationSetter;
    }
}
