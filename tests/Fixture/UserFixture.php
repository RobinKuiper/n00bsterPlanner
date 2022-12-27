<?php

namespace App\Test\Fixture;

use DateTime;
use DateTimeImmutable;

/**
 * Fixture.
 */
class UserFixture
{
    public string $table = 'users';

    public array $records;

    public function __construct()
    {
        $this->records = [
            [
                'id' => '1',
                'username' => 'Pieter',
                'password' => hash_password('ThisIsAPassword@'),
                'first_visit' => (new DateTimeImmutable('now'))->format('Y-m-d H:i:s')
            ],
            [
                'id' => '2',
                'username' => 'Jan',
                'password' => hash_password('fdgfdg353g'),
                'first_visit' => (new DateTimeImmutable('now'))->format('Y-m-d H:i:s')
            ],
        ];
    }
}
