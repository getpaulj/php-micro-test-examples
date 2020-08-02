<?php

use PHPUnit\Framework\TestCase;


class HappyClass {
    function sayHello() {
        throw new Exception('');
    }
}

class MockTest extends TestCase {
    public function testTryAnMockNonExistantClass() {
        // Create a stub for the SomeClass class.
        $stub = $this->createMock(SomeClassThatDoesntExists::class);
    }

    public function testTryAndMockMethodThatDoesntExists() {
        $mock = $this->createMock(HappyClass::class);
        $mock->method('someMethodThatDoesntExist')->willReturn('foo');
    }

    public function testTryAndMockMethodThatDoesExists() {
        // Create a stub for the SomeClass class.
        $stub = $this->createMock(HappyClass::class);

        // Configure the stub.
        $stub->method('sayHello')->willReturn('foo');
        $this->assertEquals('foo', $stub->sayHello());
    }


}
