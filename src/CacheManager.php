<?php namespace NSRosenqvist\Soma\Cache;

use Exception;
use Symfony\Component\Cache\Adapter\AdapterInterface;

class CacheManager
{
    protected $drivers = [];
    protected $default = '';

    public function use($name)
    {
        if (! isset($this->drivers[$name])) {
            throw new Exception("Cache driver hasn't been configured: ".$name);
        }

        return $this->drivers[$name];
    }

    public function setDefault($name)
    {
        if (! isset($this->drivers[$name])) {
            throw new Exception("Default cache driver hasn't been configured: ".$name);
        }

        $this->default = $name;

        return $this;
    }

    public function getDefault()
    {
        if ($this->default) {
            return $this->use($this->default);
        } else {
            return $this->use(array_first($this->drivers));
        }
    }

    public function register($name, AdapterInterface $cache, $default = false)
    {
        $this->drivers[$name] = $cache;

        if ($default) {
            $this->setDefault($name);
        }
    }

    public function __invoke($name)
    {
        return $this->use($name);
    }

    public function __call(string $method, array $parameters)
    {
        return $this->getDefault()->$method(...$parameters);
    }
}
