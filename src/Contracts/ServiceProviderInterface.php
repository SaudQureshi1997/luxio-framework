<?php

namespace Elphis\Contracts;

interface ServiceProviderInterface
{
    public function register();

    public function boot();
}
