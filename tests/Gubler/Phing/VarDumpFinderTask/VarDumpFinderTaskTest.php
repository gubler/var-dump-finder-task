<?php
/**
 * Test for VarDumpFinderTask
 *
 * @author Daryl Gubler <daryl@dev88.co>
 * @copyright Copyright (c) 2016 Daryl Gubler
 * @license MIT License
 */

namespace Gubler\Phing\VarDumpFinderTask;

use Gubler\Phing\VarDumpFinderTask\VarDumpFinderTask;

/**
 * Unit test for {@link \Gubler\Phing\VarDumpFinderTask\VarDumpFinderTask}.
 *
 * @covers \Gubler\Phing\VarDumpFinderTask\VarDumpFinderTask
 */
class VarDumpFinderTaskTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var VarDumpFinderTask
     */
    private $task;

    /**
     * @see PHPUnit_Framework_TestCase::setUp()
     */
    protected function setUp()
    {
        parent::setUp();

        $this->task = new VarDumpFinderTask();
    }

    /**
     * @test
     * @expectedException \BuildException
     */
    public function throwsBuildExceptionWhenNoFileSetWasPassed()
    {
        $this->task->main();
    }
}
