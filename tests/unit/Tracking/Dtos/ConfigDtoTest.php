<?php

namespace Tracking\Dtos;

use PHPUnit\Framework\TestCase;

/**
 * @author  <fatal.error.27@gmail.com>
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
