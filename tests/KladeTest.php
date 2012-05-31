<?php

require_once dirname(__FILE__) . '/../Klade.php';

require_once 'PHPUnit/Framework/TestCase.php';

/**
 * Test class for Klade.
 */
class KladeTest extends PHPUnit_Framework_TestCase {

    /**
     * @var Klade
     */
    protected $klade;

    protected function setUp() {
        $this->klade = new Klade;
        $this->assertNull($this->klade->setLog('test.log'));
    }

    /** Test if the log file was created
     * 
     * @covers Klade::setLog
     */
    public function testSetLog() {

        $this->assertFileExists($this->klade->filename);
    }

    /**
     * @covers Klade::addEntry
     */
    public function testAddEntry() {

        $this->assertTrue($this->klade->addEntry('an event happened right now!'));
    }

    /** Test for an expected exception when adding an invalid log message
     * @expectedException exception
     */
    public function testException() {

        $this->setExpectedException($this->klade->addEntry(''));
    }

    /**
     * @covers Klade::findEntry
     */
    public function testFindEntry() {
        $array_result = $this->klade->findEntry('an event happened right now!');

        $this->assertArrayHasKey(0, $array_result);
    }

    /**
     * @covers Klade::truncateLog
     */
    public function testTruncateLog() {

        $this->assertTrue($this->klade->truncateLog());
        $this->assertStringEqualsFile($this->klade->filename, "");
    }

}

?>