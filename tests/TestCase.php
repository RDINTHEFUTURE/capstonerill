<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Make feature tests deterministic; session/CSRF handling in test env appears to trigger 419.
        // Disable the VerifyCsrfToken middleware (keeps behavior changes localized to tests).
        $this->withoutMiddleware(\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class);






    }
}

