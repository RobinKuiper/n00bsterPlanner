<?php


namespace App\Application\Support;

use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;

class Redirect
{
    /**
     * @var ResponseInterface
     */
    protected ResponseInterface $response;

    /**
     * @param ResponseFactoryInterface $factory
     */
    public function __construct(ResponseFactoryInterface $factory)
    {
        $this->response = $factory->createResponse(302);
    }

    /**
     * @param string $to
     * @return ResponseInterface
     */
    public function __invoke(string $to): ResponseInterface
    {
        $this->response = $this->response->withHeader('Location', $to);

        return $this->response;
    }
}
