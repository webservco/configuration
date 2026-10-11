# webservco/configuration

A PHP component/library. Please see [webservco/sandbox](https://webservco.github.io/sandbox/) for an example project using these types of components.

Loads configuration from an INI or PHP file and gives typed access to the values anywhere in the application.

- Related: `webservco/dependency-container` (`ServiceContainer::getConfigurationGetter()`), `webservco/configuration-legacy` (static helper for procedural code)

---

## Installation

```shell
composer require webservco/configuration
```

---

## Usage

### Load the configuration file

Do this once in the entry point, before anything reads a value.

```php
use WebServCo\Configuration\Service\ConfigurationFileProcessor;
use WebServCo\Configuration\Service\IniServerConfigurationContainer;

// \WebServCo\Configuration\Contract\ConfigurationContainerInterface
$configurationContainer = new IniServerConfigurationContainer();
// \WebServCo\Configuration\Contract\ConfigurationFileProcessorInterface
$configurationFileProcessor = new ConfigurationFileProcessor(
    $configurationContainer->getConfigurationDataProcessor(),
    $configurationContainer->getConfigurationLoader(),
    $configurationContainer->getConfigurationSetter(),
);
// Reads {projectPath}/config/.env.ini
$configurationFileProcessor->processConfigurationFile($projectPath, 'config', '.env.ini');
```

### Read values

```php
use WebServCo\Configuration\Factory\ServerConfigurationGetterFactory;

$configurationGetterFactory = new ServerConfigurationGetterFactory();
// \WebServCo\Configuration\Contract\ConfigurationGetterInterface
$configurationGetter = $configurationGetterFactory->createConfigurationGetter();

$host = $configurationGetter->getString('DB_HOST');
$allowedHosts = $configurationGetter->getArray('ALLOWED_HOSTS');
```

---

## Contracts

| Interface | Implementation | Purpose |
| --- | --- | --- |
| `Contract\ConfigurationGetterInterface` | `Service\ServerConfigurationGetter` | Typed getters: `getString()`, `getInt()`, `getFloat()`, `getBool()`, `getArray()`. |
| `Contract\ConfigurationGetterFactoryInterface` | `Factory\ServerConfigurationGetterFactory` | Creates the getter. |
| `Contract\ConfigurationSetterInterface` | `Service\ServerConfigurationSetter` | `set()` a value or `append()` to a list. |
| `Contract\ConfigurationFileProcessorInterface` | `Service\ConfigurationFileProcessor` | Loads a file and stores every value. |
| `Contract\ConfigurationLoaderInterface` | `Service\IniConfigurationLoader`, `Service\PHPConfigurationLoader` | Reads a file into an array. |
| `Contract\ConfigurationContainerInterface` | `Service\IniServerConfigurationContainer` | Wires the INI loader with the setter. |

Namespaces are relative to `WebServCo\Configuration\`.

---

## Configuration

```ini
; Sections are only for readability, they are ignored when parsing.
[Database]

DB_HOST = db
DB_PORT = 3306

[Application]

; A list, read with getArray().
ALLOWED_HOSTS[] = example.ddev.site
ALLOWED_HOSTS[] = localhost
```

Keep `config/.env.ini` out of Git and commit an example such as `config/.env.development.ini` instead.

---

## Notes

Values are stored in `$_SERVER`, with the key in upper case and prefixed with `APP_`. `DB_HOST` ends up in `$_SERVER['APP_DB_HOST']`. The file processor also sets `PROJECT_PATH`.

`INI_SCANNER_TYPED` keeps `true`, `false`, `null` and plain integers typed. A quoted number like `"3306"` stays a string and `getInt()` rejects it. Getters throw an `OutOfBoundsException` for a missing key and an `UnexpectedValueException` for a wrong type.

`PHPConfigurationLoader` loads a PHP file that returns an array. `webservco/route` uses it for route files.

---

## Development

```shell
# Start project.
ddev start
# Install dependencies.
ddev c u
# Run all validations.
ddev c c
# Run unit tests.
ddev c test:dox
```

---

## License

[MIT](LICENSE.md)
