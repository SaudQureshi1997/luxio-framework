<?php

if (!function_exists('base_path')) {
    function base_path($path = null)
    {
        if ($path) {
            $path = trim($path, '/');
            return getcwd() . '/' . $path;
        }

        return getcwd();
    }
}

if (!function_exists('public_path')) {
    function public_path($path = null)
    {
        if ($path) {
            return base_path('public/' . $path);
        }

        return base_path('public');
    }
}

if (!function_exists('storage_path')) {
    function storage_path($path = null)
    {
        if ($path) {
            return base_path('storage/' . $path);
        }

        return base_path('storage');
    }
}

if (!function_exists('dd')) {
    function dd($vars)
    {
        throw new \Exception(json_encode($vars));
    }
}

if (!function_exists('is_json')) {
    function is_json($json)
    {
        if (is_array($json) or $json instanceof ArrayAccess) {
            return false;
        }
        return (json_decode($json, true) == NULL) ? false : true;
    }
}


if (!function_exists('env')) {
    function env($name, $default = null)
    {
        if ($data = getenv($name)) {
            return $data;
        }

        return $default;
    }
}
