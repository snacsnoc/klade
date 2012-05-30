<?php

require_once dirname(__FILE__) . '/../Klade.php';

require_once 'PHPUnit/Framework/TestCase.php';

class AddEntryTest extends PHPUnit_Framework_TestCase {

    public function setUp() {
        $this->klade = new Klade;
        $this->klade->setLog('test.log');
        $this->assertFileExists($this->klade->filename);
    }

    /** Test for an expected exception when adding an invalid message
     * @expectedException exception
     */
    public function testException() {

        $this->setExpectedException($this->klade->addEntry(''));
    }

    public function testAddEntry() {

        $this->assertTrue($this->klade->addEntry('an event happened right now!'));
    }

}

?>
