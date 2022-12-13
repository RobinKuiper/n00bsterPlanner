<?php

namespace App\Action\User;

use App\Domain\User\Service\UserUpdater;
use App\Renderer\JsonRenderer;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class UserUpdaterAction
{
    /**
     * @var JsonRenderer
     */
    private JsonRenderer $renderer;

    /**
     * @var UserUpdater
     */
    private UserUpdater $userUpdater;

    /**
     * @param UserUpdater $userUpdater
     * @param JsonRenderer $renderer
     */
    public function __construct(UserUpdater $userUpdater, JsonRenderer $renderer)
    {
        $this->userUpdater = $userUpdater;
        $this->renderer = $renderer;
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return ResponseInterface
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        // Extract the form data from the request body
        $data = (array)$request->getParsedBody();

        // Invoke the Domain with inputs and retain the result
        $user = $this->userUpdater->update($data);

        // Build the HTTP response
        return $this->renderer
            ->json($response, ['user_id' => $user->getId()])
            ->withStatus(StatusCodeInterface::STATUS_CREATED);
    }
}
