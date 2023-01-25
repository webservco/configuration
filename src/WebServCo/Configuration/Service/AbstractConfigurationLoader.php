<?php

declare(strict_types=1);

namespace WebServCo\Configuration\Service;

use OutOfBoundsException;

use function is_readable;

abstract class AbstractConfigurationLoader
{
    protected function validateFilePath(string $filePath): bool
    {
        if (!is_readable($filePath)) {
            /**
             * Since environment is probably not yet set in the implementing application at this point,
             * it will usually not be possible to show separate messages, or use logging,
             * so for security reasons do not add the actual file path in this error message.
             */
            throw new OutOfBoundsException('Configuration file path not readable.');
        }

        return true;
    }
}
