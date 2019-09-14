<?php

namespace Elphis\Utils;

use Exception;

class Config
{
    protected $dir;
    protected $configs;

    public function __construct($configDir)
    {
        if (!is_dir($configDir)) {
            throw new Exception("$configDir does not exist");
        }

        $this->dir = rtrim($configDir, '/');

        $this->initialize();
    }

    public function initialize()
    {
        $files = glob($this->dir . '/*.php');
        foreach ($files as $file) {
            $names = explode('/', $file);
            $name = end($names);
            $name = str_replace('.php', '', $name);
            $this->configs[$name] = require_once($file);
        }
    }

    /**
     * name of the config
     *
     * @param string $name
     * @return void
     */
    public function get(string $name)
    {
        $name = strtolower($name);
        if (strpos($name, '.') === false) {
            return $this->has($name) ? $this->configs[$name] : null;
        }

        $keys = explode('.', $name);
        $value = $this->configs;

        foreach ($keys as $key) {

            if (\array_key_exists($key, $value)) {
                $value = $value[$key];
                continue;
            }

            $value = null;
            break;
        }

        return $value;
    }

    /**
     * check if configuration exists
     *
     * @param string $name
     * @return boolean
     */
    public function has(string $name)
    {
        $name = strtolower($name);
        if (strpos($name, '.') === false) {
            return isset($this->configs[$name]);
        }

        $keys = explode('.', $name);
        $value = $this->configs;

        foreach ($keys as $key) {

            if (\array_key_exists($key, $value)) {
                $value = $value[$key];
                continue;
            }

            return false;
        }

        return true;
    }
}
