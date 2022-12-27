<?php

namespace App\Test\TestCase\Action\API\Auth;

use App\Test\Fixture\UserFixture;
use App\Test\Traits\AppTestTrait;
use Fig\Http\Message\StatusCodeInterface;
use PHPUnit\Framework\TestCase;
use Selective\TestTrait\Traits\DatabaseTestTrait;

/**
 * Test.
 *
 * @coversDefaultClass \App\Application\Action\Api\Auth\LoginAction
 */
class LoginActionTest extends TestCase
{
    use AppTestTrait;
    use DatabaseTestTrait;

    public function testLoginUser(): void
    {
//        Chronos::setTestNow('2021-01-01 00:00:00'); TODO: Need?

        $this->insertFixtures([UserFixture::class]);

        $request = $this->createJsonRequest(
            'POST',
            '/api/authentication/login',
            [
                'username' => 'Pieter',
                'password' => 'ThisIsAPassword@'
            ]
        );

        $response = $this->app->handle($request);

        // No logger errors
        $this->assertSame([], $this->getLoggerErrors());
//        $this->assertTrue($this->getLogger()->hasInfoThatContains('User registered successfully'));

        // Check response
        $this->assertSame(StatusCodeInterface::STATUS_OK, $response->getStatusCode());
        $this->assertJsonContentType($response);
//        $this->assertJsonData(['customer_id' => 1], $response);
        $this->assertJsonValue(true, 'success', $response);

        // TODO: Check Session

        // Check logger
//        $this->assertTrue($this->getLogger()->hasInfoThatContains('User registered successfully'));

        // Check database
//        $this->assertTableRowCount(1, 'users');

        $expected = [
            'username' => 'Pieter2',
            'visitorId' => null
        ];

//        $this->assertTableRow($expected, 'users', 1);
    }
}
