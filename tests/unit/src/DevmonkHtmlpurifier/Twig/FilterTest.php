<?php

use DevmonkHtmlpurifier\Module;
use DevmonkHtmlpurifier\Twig\Filter;

class FilterTest extends \PHPUnit_Framework_TestCase
{
    /** @var Filter */
    protected $sut;

    public function setUp()
    {
        Module::setConstants();

        $this->sut = new Filter();
    }

    public function testMissingServiceLocatorReturnsNull()
    {
        // ----------------------------------------------------------------
        // execute
        //
        $actual = Filter::purify('dirtyHtml');

        // ----------------------------------------------------------------
        // test the results
        //
        $this->assertSame(null, $actual);
    }

    public function testMissingServiceReturnsNull()
    {
        // ----------------------------------------------------------------
        // setup test parameters
        //
        $mockServiceLocator = $this->getMock('Zend\ServiceManager\ServiceLocatorInterface');
        Filter::setServiceLocator($mockServiceLocator);

        // ----------------------------------------------------------------
        // setup mock expectations
        //
        $mockServiceLocator
            ->expects($this->once())
            ->method('get')
            ->with(Module::SERVICE_NAME)
            ->will($this->returnValue('Not an instance of \\HTMLPurifier.'));

        // ----------------------------------------------------------------
        // execute
        //
        $actual = Filter::purify('dirtyHtml');

        // ----------------------------------------------------------------
        // test the results
        //
        $this->assertSame(null, $actual);
    }

    public function testPurifyCallsPurifyOfService()
    {
        // ----------------------------------------------------------------
        // setup test parameters
        //
        $inputText = 'dirtyHtml';
        $outputText = 'purified-glorified!';
        $mockServiceLocator = $this->getMock('Zend\ServiceManager\ServiceLocatorInterface');
        $mockPurifier = $this->getMock('HTMLPurifier');
        Filter::setServiceLocator($mockServiceLocator);

        // ----------------------------------------------------------------
        // setup mock expectations
        //
        $mockServiceLocator
            ->expects($this->once())
            ->method('get')
            ->with(Module::SERVICE_NAME)
            ->will($this->returnValue($mockPurifier));
        $mockPurifier
            ->expects($this->once())
            ->method('purify')
            ->with($inputText)
            ->will($this->returnValue($outputText));

        // ----------------------------------------------------------------
        // execute
        //
        $actual = Filter::purify($inputText);

        // ----------------------------------------------------------------
        // test the results
        //
        $this->assertSame($outputText, $actual);
    }
}