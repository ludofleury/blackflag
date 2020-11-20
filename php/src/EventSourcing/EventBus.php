<?php
declare(strict_types=1);

namespace EventSourcing;

interface EventBus
{
    public function dispatch(Stream $stream): void;
}