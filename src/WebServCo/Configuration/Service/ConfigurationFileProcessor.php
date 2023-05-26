<?php

declare(strict_types=1);

namespace WebServCo\Configuration\Service;

use WebServCo\Configuration\Contract\ConfigurationDataProcessorInterface;
use WebServCo\Configuration\Contract\ConfigurationFileProcessorInterface;
use WebServCo\Configuration\Contract\ConfigurationLoaderInterface;
use WebServCo\Configuration\Contract\ConfigurationSetterInterface;

use function sprintf;

use const DIRECTORY_SEPARATOR;

final class ConfigurationFileProcessor implements ConfigurationFileProcessorInterface
{
    private ConfigurationDataProcessorInterface $configurationDataProcessor;
    private ConfigurationLoaderInterface $configurationLoader;
    private ConfigurationSetterInterface $configurationSetter;
    public function __construct(ConfigurationDataProcessorInterface $configurationDataProcessor, ConfigurationLoaderInterface $configurationLoader, ConfigurationSetterInterface $configurationSetter)
    {
        $this->configurationDataProcessor = $configurationDataProcessor;
        $this->configurationLoader = $configurationLoader;
        $this->configurationSetter = $configurationSetter;
    }
    public function processConfigurationFile(string $projectPath, string $configurationDirectory, string $configurationFile): bool
    {
        $configurationData = $this->configurationLoader->loadFromFile(
            sprintf('%s%s%s%s', $projectPath, $configurationDirectory, DIRECTORY_SEPARATOR, $configurationFile),
        );
        $this->configurationDataProcessor->process($configurationData);
        // Set project path in in the configuration so that we don't have to send it everywhere from here.
        $this->configurationSetter->set('PROJECT_PATH', $projectPath);
        return true;
    }
}
