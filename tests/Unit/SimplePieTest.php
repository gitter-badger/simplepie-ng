<?php
/**
 * Copyright (c) 2017 Ryan Parman <http://ryanparman.com>.
 * Copyright (c) 2017 Contributors.
 *
 * http://opensource.org/licenses/Apache2.0
 */

declare(strict_types=1);

namespace SimplePie\Test\Unit;

use SimplePie\SimplePie;

class SimplePieTest extends AbstractTestCase
{
    public function testFailMe(): void
    {
        new SimplePie();
    }
}
