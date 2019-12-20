<?php

namespace Tracking\Dtos;

use PHPUnit\Framework\TestCase;

/**
 * @author  Alexej Beirith <alexej.beirith@arvato.com>
 */
class ConfigDtoTest extends TestCase
{
    public function testDto(): void
    {
        $config = new ConfigDto();
        TestCase::assertEmpty($config->data);
        TestCase::assertNull($config->VACATION_DAYS_PER_YEAR);

        $config->VACATION_DAYS_PER_YEAR = 100;
        TestCase::assertEquals(100, $config->VACATION_DAYS_PER_YEAR);
    }
}
