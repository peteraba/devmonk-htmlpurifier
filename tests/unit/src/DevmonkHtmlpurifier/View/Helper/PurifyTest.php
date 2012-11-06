<?php

use DevmonkHtmlpurifier\View\Helper\Purify;
use DevmonkHtmlpurifier\Module;

class PurifyTest extends \PHPUnit_Framework_TestCase
{
    /** @var Purify */
    protected $sut;

    /** @var \HTMLPurifier */
    protected $mockPurifier;

    public function setUp()
    {
        Module::setConstants();

        /** @var \HTMLPurifier $mockPurifier  */
        $this->mockPurifier = $this->getMock('\HTMLPurifier');

        $this->sut = new Purify($this->mockPurifier);
    }

    public function testSetPurifierGetsCalledOnInvode()
    {
        // ----------------------------------------------------------------
        // setup test parameters
        //
        $dirtyHtml = 'dirtyHtml';
        $expected = 'purified-glorified!';

        // ----------------------------------------------------------------
        // setup mock expectations
        //
        $this->mockPurifier
            ->expects($this->once())
            ->method('purify')
            ->with($dirtyHtml)
            ->will($this->returnValue($expected));

        // ----------------------------------------------------------------
        // execute
        //
        $sut = $this->sut;
        $actual = $sut($dirtyHtml);

        // ----------------------------------------------------------------
        // test the results
        //
        $this->assertEquals($actual, $expected);
    }
}