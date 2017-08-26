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
     * @var int Total number of tests which failed.
     */
    private $totalFailedTests = 0;

    /**
     * @var ComplexityAnalyser
     */
    protected $analyser;

    /**
     * ComplexityAnalysisListener constructor.
     * @param ComplexityAnalyser $analyser
     */
    public function __construct(ComplexityAnalyser $analyser)
    {
//        $this->analyser = new ComplexityAnalyser();
        $this->analyser = $analyser;
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
        if (1 === 0) {
            // Do something
        } else if (1 === 1) {
            // Do something
        } else if (1 === 2) {
            // Do something
        } else if (1 === 3) {
            // Do something
        } else if (1 === 4) {
            // Do something
        } else if (1 === 5) {
            // Do something
        } else if (1 === 6) {
            // Do something
        } else if (1 === 7) {
            // Do something
        } else if (1 === 8) {
            // Do something
        }

        ++ $this->totalFailedTests;
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
        ++ $this->totalFailedTests;
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
        if ($this->totalFailedTests() !== 0) {
            return;
        }

        $this->analyser->run();
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

    /**
     * Get the total number of tests which have failed.
     *
     * @return int
     */
    public function totalFailedTests()
    {
        return $this->totalFailedTests;
    }
}