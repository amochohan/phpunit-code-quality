# PHPUnit Code Quality

Automatically check code quality once your PHPUnit tests pass.

## Installation

Installation is performed via Composer.

```bash
composer require --dev drawmyattention/phpunit-code-quality
```

Register a test listener to your `phpunit.xml` file:

```xml
<listeners>
    <listener class="DrawMyAttention\CodeQuality\Listeners\ComplexityAnalysisListener">
        <arguments>
            <object class="DrawMyAttention\CodeQuality\ComplexityAnalyser"/>
            <bool>true</bool>
        </arguments>
    </listener>
</listeners>
```

## Configuring Project Settings

You can define which directories are scanned for code quality checking, as well as which directories and files are excluded. Sensible defaults are provided. By default, code stored in your `src` directory is checked, and any code in your `tests` directory is excluded.

You can create a `complexity-analyser-config.php` file in your project's root directory to specify alternative settings. See the provided config file, or copy and paste the following example:

```php

return [

    // Directories which should be checked for code quality
    'scan_directories' => [
        'src',
    ],

    // Directories which should not be checked
    'excluded_directories' => [
        'app/Support',
        'tests',
    ],

    // Files which should not be checked
    'excluded_files' => [
        'app/Http/Controllers/SomeController.php',
    ],

];
```

Because this application utilises [PHP Mess Detector](https://github.com/phpmd/phpmd) to check code quality, you can define which rules should be applied when checking code quality. [A full list of rules is available here](https://phpmd.org/rules/index.html).


## Contributing

If you find a bug or would like to contribute to the development of this package, please submit a pull-request (with tests if possible).



