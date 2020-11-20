<?php

namespace EventSourcing;

interface EventBus
{
    public function dispatch(Stream $stream): void;
}