<?php

namespace DrawMyAttention\CodeQuality\Listeners;

use Exception;
use PHPUnit_Framework_Test;
use PHPUnit_Framework_TestSuite;
use PHPUnit_Framework_TestListener;
use PHPUnit_Framework_AssertionFailedError;
use DrawMyAttention\CodeQuality\ComplexityAnalyser;

class ComplexityAnalysisListener implements PHPUnit_Framework_TestListener
{
    /**
     * @var bool Is the listener enabled?
     */
    private $enabled = true;

    /**
     * @var int Total number of errors in the suite.
     */
    private $totalErrors = 0;

    /**
     * ComplexityAnalysisListener constructor.
     *
     * @param bool $enabled Is the listener enabled?
     */
    public function __construct($enabled = true)
    {
        $this->enabled = $enabled;
    }

    /**
     * An error occurred.
     *
     * @param PHPUnit_Framework_Test $test
     * @param Exception $e
     * @param float $time
     */
    public function addError(PHPUnit_Framework_Test $test, Exception $e, $time)
    {
        ++$this->totalErrors;
    }

    /**
     * A failure occurred.
     *
     * @param PHPUnit_Framework_Test $test
     * @param PHPUnit_Framework_AssertionFailedError $e
     * @param float $time
     */
    public function addFailure(PHPUnit_Framework_Test $test, PHPUnit_Framework_AssertionFailedError $e, $time)
    {
        ++$this->totalErrors;
    }

    /**
     * Incomplete test.
     *
     * @param PHPUnit_Framework_Test $test
     * @param Exception $e
     * @param float $time
     */
    public function addIncompleteTest(PHPUnit_Framework_Test $test, Exception $e, $time)
    {
        // TODO: Implement addIncompleteTest() method.
    }

    /**
     * Risky test.
     *
     * @param PHPUnit_Framework_Test $test
     * @param Exception $e
     * @param float $time
     */
    public function addRiskyTest(PHPUnit_Framework_Test $test, Exception $e, $time)
    {
        // TODO: Implement addRiskyTest() method.
    }

    /**
     * Skipped test.
     *
     * @param PHPUnit_Framework_Test $test
     * @param Exception $e
     * @param float $time
     */
    public function addSkippedTest(PHPUnit_Framework_Test $test, Exception $e, $time)
    {
        // TODO: Implement addSkippedTest() method.
    }

    /**
     * A test suite started.
     *
     * @param PHPUnit_Framework_TestSuite $suite
     */
    public function startTestSuite(PHPUnit_Framework_TestSuite $suite)
    {
        // TODO: Implement startTestSuite() method.
    }

    /**
     * A test suite ended.
     *
     * @param PHPUnit_Framework_TestSuite $suite
     */
    public function endTestSuite(PHPUnit_Framework_TestSuite $suite)
    {
        if ($this->totalErrors === 0) {
            printf("\nTestSuite '%s' complete.\n", $suite->getName());
            $console = new ComplexityAnalyser();
            $console->run();
        }

    }

    /**
     * A test started.
     *
     * @param PHPUnit_Framework_Test $test
     */
    public function startTest(PHPUnit_Framework_Test $test)
    {
        // TODO: Implement startTest() method.
    }

    /**
     * A test ended.
     *
     * @param PHPUnit_Framework_Test $test
     * @param float $time
     */
    public function endTest(PHPUnit_Framework_Test $test, $time)
    {
        // TODO: Implement endTest() method.
    }
}