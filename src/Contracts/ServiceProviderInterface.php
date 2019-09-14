<?php

namespace elphis\Contracts;

interface ServiceProviderInterface
{
    public function register();

    public function boot();
}
