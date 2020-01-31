# SOMA Cache

## Installation

```sh
composer require nsrosenqvist/soma-cache
```

## Usage

**Example configuration:**
```php
<?php return [
    'default' => 'files',

    'stores' => [
        'files' => [
            'driver' => 'filesystem',
            'directory' => get_path('cache').'/files',
        ],
    ],
];
```

To see how to configure all supported [cache adapters](https://symfony.com/doc/4.4/components/cache.html#available-cache-adapters) review [how the configuration is processed](https://github.com/nsrosenqvist/soma-cache/blob/master/src/CacheProvider.php) in `CacheProvider::resolveAdapter`.

## License

MIT