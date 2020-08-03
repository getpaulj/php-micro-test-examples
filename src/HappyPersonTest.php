<?php

use PHPUnit\Framework\TestCase;

interface DateProvider {
    function now():  DateTime;
}


class HappyPerson
{
    private $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    function sayHello($name)
    {
        $hello = sprintf("Hello %s", $name);
        $myNameIs = sprintf("my name is %s", $this->name);

        $now = new DateTime();

        $interestingFact = sprintf("did you know there has been precisely %s seconds since epoc", $now->getTimestamp());

        return sprintf("%s %s %s", $hello, $myNameIs, $interestingFact);
    }
}

class HappyPersonTest extends TestCase
{
    public function testSayHelloSupplyingNameTo() {

        $myName = 'Paul';
        $happyClass = new HappyPerson($myName);

        $someOneElse = 'someOneElse';
        $actual = $happyClass->sayHello($someOneElse);
        $expected = 'Hello ' . $someOneElse . ' my name is ' . $myName;

        $this->assertEquals($expected, $actual);
    }

}
