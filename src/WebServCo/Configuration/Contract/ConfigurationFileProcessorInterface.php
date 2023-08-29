<?php

declare(strict_types=1);

namespace WebServCo\Configuration\Contract;

interface ConfigurationFileProcessorInterface
{
    /**
     * Loads configuration settings from file.
     * Sets configuration as environment variables.
     * Since using a ServerConfigurationSetter,
     * after this method is called the configuration settings will be available in the $_SERVER superglobal
     * and could be used directly (or better, use a ConfigurationGetterInterface)
     */
    public function processConfigurationFile(string $projectPath, string $configurationDirectory, string $configurationFile): bool;
}
