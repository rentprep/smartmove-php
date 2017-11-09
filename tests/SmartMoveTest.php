<?php

namespace SmartMove\Tests;

use SmartMove\SmartMove;

use PHPUnit\Framework\TestCase;

class SmartMoveTest extends TestCase {

    public function testSandboxMode() {
        SmartMove::setSandboxMode();
        $this->assertSame(SmartMove::getApiBase(), SmartMove::SANDBOX_API_ENDPOINT);

        SmartMove::setSandboxMode(false);
        $this->assertSame(SmartMove::getApiBase(), SmartMove::API_ENDPOINT);
    }

}
