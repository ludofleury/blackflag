<?php

namespace Tests\Controller;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/debug', name: 'test_debug')]
class TestController
{
    public function __invoke(LoggerInterface $logger): Response
    {
        $logger->info('test debug controller called');
        return new Response('<html lang="en"><body>test debug</body></html>');
    }
}