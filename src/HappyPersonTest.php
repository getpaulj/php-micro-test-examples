<?php

use PHPUnit\Framework\TestCase;


interface DateTimeProvider{
    function now(): DateTime;
}

class HappyPerson
{
    private $name;
    private $dateTimeProvider;

    public function __construct(string $name, DateTimeProvider $dateTimeProvider)
    {
        $this->name = $name;
        $this->dateTimeProvider = $dateTimeProvider;
    }

    function sayHello($name)
    {
        $hello = sprintf("Hello %s", $name);
        $myNameIs = sprintf("my name is %s", $this->name);

        $now = $this->dateTimeProvider->now();

        $interestingFact = sprintf("did you know there has been precisely %s seconds since epoc", $now->getTimestamp());

        return sprintf("%s %s, %s" , $hello, $myNameIs, $interestingFact);
    }
}

class HappyPersonTest extends TestCase
{
    public function testSayHelloSupplyingNameTo() {

        $now = new DateTime();

        $myName = 'Paul';
        $dateTimeMock = $this->createMock(DateTimeProvider::class);
        $happyClass = new HappyPerson($myName, $dateTimeMock);

        $dateTimeMock->expects($this->once())->method('now')->willReturn($now);
        $someOneElse = 'someOneElse';
        $actual = $happyClass->sayHello($someOneElse);

        $interestingFact = sprintf(", did you know there has been precisely %s seconds since epoc", $now->getTimestamp());

        $expected = 'Hello ' . $someOneElse . ' my name is ' . $myName . $interestingFact;

        $this->assertEquals($expected, $actual);
    }
}
