<?php namespace NSRosenqvist\Soma\Cache;

use Exception;
use Soma\ServiceProvider;
use Psr\Container\ContainerInterface;

use NSRosenqvist\Soma\Cache\CacheManager;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Component\Cache\Adapter\ApcuAdapter;
use Symfony\Component\Cache\Adapter\ChainAdapter;
use Symfony\Component\Cache\Adapter\DoctrineAdapter;
use Symfony\Component\Cache\Adapter\MemcachedAdapter;
use Symfony\Component\Cache\Adapter\PdoAdapter;
use Symfony\Component\Cache\Adapter\PhpArrayAdapter;
use Symfony\Component\Cache\Adapter\PhpFilesAdapter;
use Symfony\Component\Cache\Adapter\ProxyAdapter;
use Symfony\Component\Cache\Adapter\RedisAdapter;

class CacheProvider extends ServiceProvider
{
    protected function resolveAdapter($def, ContainerInterface $c)
    {
        $driver = strtolower($def['driver'] ?? 'filesystem');

        $namespace = $def['namespace'] ?? '';
        $lifetime = $def['lifetime'] ?? 0;
        $version = $def['version'] ?? null;
        $serialize = $def['serialized'] ?? true;
        $provider = $def['provider'] ?? null;
        $directory = $def['directory'] ?? null;
        $client = $def['client'] ?? null;
        $options = $def['options'] ?? [];
        $connection = $def['connection'] ?? null;
        $adapters = $def['adapters'] ?? [];
        $file = $def['file'] ?? '';
        $backup = $def['backup'] ?? null;
        $pool = $def['pool'] ?? null;

        switch ($driver) {
            case "apcu": return new ApcuAdapter($namespace, $lifetime, $version);
            case "array": return new ArrayCache($lifetime, $serialize);
            case "doctrine": return new DoctrineAdapter($c->get($provider), $lifetime);
            case "filesystem": return new FilesystemAdapter($namespace, $lifetime, $directory);
            case "memcached": return new MemcachedAdapter($c->get($client), $namespace, $lifetime);
            case "pdo": return new MemcachedAdapter($c->get($connection), $namespace, $lifetime, $options);
            case "php-array": return new PhpArrayAdapter($file, $c->get($backup));
            case "php-files": return new PhpFilesAdapter($namespace, $lifetime, $directory);
            case "php-files": return new ProxyAdapter($pool, $namespace, $lifetime);
            case "redis": return new RedisAdapter($connection, $namespace, $lifetime);
            case "chain": return new ChainAdapter(array_map(function ($item) use ($c) {
                    return $this->resolveAdapter($item, $c);
                }, $adapters), $lifetime);
        }

        return null;
    }
    public function getFactories() : array
    {
        return [
            'cache' => function(ContainerInterface $c) {
                $cache = new CacheManager();

                foreach (config('cache.stores', []) as $name => $store) {
                    $cache->register($name, $this->resolveAdapter($store, $c));
                }

                if ($default = config('cache.default')) {
                    $cache->setDefault($default);
                }

                return $cache;
            },
        ];
    }
}