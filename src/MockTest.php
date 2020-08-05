<?php

use PHPUnit\Framework\TestCase;


class HappyPerson {
    function sayHello($toName) {
        throw new Exception('Not implemented');
    }
}

class MockTest extends TestCase {
    public function testTryAndMockClassThatDoesntExist() {
        $mock = $this->createMock(SomeClassThatDoesntExists::class);
    }

    public function testTryAndMockMethodThatDoesntExists() {
        $mock = $this->createMock(HappyPerson::class);
        $mock->method('someMethodThatDoesntExist')->willReturn('foo');
    }

    public function testFailsIfNotCalled() {
        $mock = $this->createMock(HappyPerson::class);

        $mock->expects($this->atLeastOnce())->method('sayHello')->willReturn('foo');

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

        // given
        $sayHelloTo = 'Donald';
        $stub = $this->createMock(HappyPerson::class);
        $expected = 'foo';
        $stub->expects($this->atLeastOnce())->method('sayHello')->willReturn($expected);

        // when
       $actual = $stub->sayHello($sayHelloTo);

       // then
        $this->assertEquals($expected, $actual);
    }


}
