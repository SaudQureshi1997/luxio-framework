<?php

namespace Elphis\Contracts;

interface RequestInterface
{
    public function initialize();

    public function getServerParams(): array;

    public function getServerParam(string $name): ?string;

    public function getMethod(): string;

    public function getBody(): array;

    public function getQueryParams(): array;

    public function getQueryParam(string $name): ?string;

    public function getRawContent(): string;
}
