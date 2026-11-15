<?php

namespace Tests\Unit;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_the_application_uses_indonesian_as_its_default_locale(): void
    {
        $this->assertSame('id', config('app.locale'));
    }
}
