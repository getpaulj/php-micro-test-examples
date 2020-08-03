<?php

use PHPUnit\Framework\TestCase;


class HappyPerson {
    function sayHello($toName) {
        throw new Exception('');
    }
}

class MockTest extends TestCase {
    public function testTryAndMockClassThatDoesntExist() {
        $stub = $this->createMock(SomeClassThatDoesntExists::class);
    }

    public function testTryAndMockMethodThatDoesntExists() {
        $mock = $this->createMock(HappyPerson::class);
        $mock->method('someMethodThatDoesntExist')->willReturn('foo');
    }

    public function testFailsIfNotCalled() {
        $stub = $this->createMock(HappyPerson::class);
        $stub->expects($this->once())->method('sayHello')->willReturn('foo');
        $this->assertEquals(true, true);
    }

    public function testCalledToManyTimes() {
        $stub = $this->createMock(HappyPerson::class);
        $sayHelloTo = 'Donald';
        $stub->expects($this->once())->method('sayHello')->with($sayHelloTo)->willReturn('foo');
        $stub->sayHello($sayHelloTo);
        $stub->sayHello($sayHelloTo);
    }

    public function testCalledCorrectly() {
        $sayHelloTo = 'Donald';
        $stub = $this->createMock(HappyPerson::class);
        $stub->expects($this->atLeastOnce())->method('sayHello')->willReturn('foo');
        $this->assertEquals('foo', $stub->sayHello($sayHelloTo));
    }


}
