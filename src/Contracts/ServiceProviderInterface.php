<?php

namespace Luxio\Contracts;

interface ServiceProviderInterface
{
    public function register();

    public function boot();
}
