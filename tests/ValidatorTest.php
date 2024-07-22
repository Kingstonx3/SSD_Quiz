<?php

use PHPUnit\Framework\TestCase;
use App\Validator;

class ValidatorTest extends TestCase
{
    public function testIsXSS()
    {
        $validator = new Validator();
        $this->assertTrue($validator->isXSS('<script>alert("XSS")</script>'));
        $this->assertFalse($validator->isXSS('Safe input'));
    }

    public function testIsSQLInjection()
    {
        $validator = new Validator();
        $this->assertTrue($validator->isSQLInjection('SELECT * FROM users'));
        $this->assertFalse($validator->isSQLInjection('Safe input'));
    }
}
