<?php

declare(strict_types=1);

namespace WebServCo\Configuration\Service;

use Override;
use WebServCo\Configuration\Contract\ConfigurationDataProcessorInterface;
use WebServCo\Configuration\Contract\ConfigurationFileProcessorInterface;
use WebServCo\Configuration\Contract\ConfigurationLoaderInterface;
use WebServCo\Configuration\Contract\ConfigurationSetterInterface;

use function rtrim;
use function sprintf;

use const DIRECTORY_SEPARATOR;

final class ConfigurationFileProcessor implements ConfigurationFileProcessorInterface
{
    public function __construct(
        private ConfigurationDataProcessorInterface $configurationDataProcessor,
        private ConfigurationLoaderInterface $configurationLoader,
        private ConfigurationSetterInterface $configurationSetter,
    ) {
    }

    #[Override]
    public function processConfigurationFile(
        string $projectPath,
        string $configurationDirectory,
        string $configurationFile,
    ): bool {
        // Make sure path contains trailing slash (trim + add back).
        $projectPath = rtrim($projectPath, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        $configurationData = $this->configurationLoader->loadFromFile(
            sprintf('%s%s%s%s', $projectPath, $configurationDirectory, DIRECTORY_SEPARATOR, $configurationFile),
        );
        $this->configurationDataProcessor->process($configurationData);
        // Set project path in in the configuration so that we don't have to send it everywhere from here.
        $this->configurationSetter->set('PROJECT_PATH', $projectPath);

        return true;
    }
}
