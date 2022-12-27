<?php

namespace App\Test\Fixture;

use DateTime;
use DateTimeImmutable;

/**
 * Fixture.
 */
class EventFixture
{
    public string $table = 'events';

    public array $records;

    public function __construct()
    {
        $this->records = [
            [
                'id' => 1,
                'identifier' => '2def32d0-240f-4793-a397-074d2b82692c',
                'title' => 'Test Event',
                'description' => 'Test Event Description',
                'start_date' => (new DateTimeImmutable('now'))->format('Y-m-d'),
                'end_date' => (new DateTimeImmutable('now'))->format('Y-m-d'),
                'ownedBy_id' => 1
            ],
            [
                'id' => 2,
                'identifier' => 'b1533600-f4e3-4c96-a8ab-2c496de62522',
                'title' => 'Test Event 2',
                'description' => 'Test Event 2 Description',
                'start_date' => (new DateTimeImmutable('now'))->format('Y-m-d'),
                'end_date' => (new DateTimeImmutable('now'))->format('Y-m-d'),
                'ownedBy_id' => 2
            ],
        ];
    }
}
