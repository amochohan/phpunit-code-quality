<?php

namespace DrawMyAttention\CodeQuality\Tests\Unit;

use Exception;
use Mockery as m;
use PHPUnit_Framework_Test;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_TestSuite;
use DrawMyAttention\CodeQuality\ComplexityAnalyser;
use DrawMyAttention\CodeQuality\Listeners\ComplexityAnalysisListener;

class ComplexityAnalysisListenerTest extends TestCase
{
    public function tearDown()
    {
        m::close();
        parent::tearDown();
    }

    /** @test */
    public function it_can_be_instantiated()
    {
        $analyser = m::mock(ComplexityAnalyser::class);
        $this->assertInstanceOf(ComplexityAnalysisListener::class, new ComplexityAnalysisListener($analyser));
    }

    /** @test */
    public function it_tracks_how_many_tests_have_failed()
    {
        $analyser = m::mock(ComplexityAnalyser::class);
        $listener = new ComplexityAnalysisListener($analyser);
        $this->assertEquals(0, $listener->totalFailedTests());

        $failingTest = m::mock(PHPUnit_Framework_Test::class);
        $exception = m::mock(Exception::class);
        $time = 0;

        $listener->addError($failingTest, $exception, $time);

        $this->assertEquals(1, $listener->totalFailedTests());

        $listener->addError($failingTest, $exception, $time);
        $listener->addError($failingTest, $exception, $time);

        $this->assertEquals(3, $listener->totalFailedTests());
    }

    /** @test */
    public function it_runs_the_code_analyser_if_there_are_no_test_failures()
    {
        $analyser = m::mock(ComplexityAnalyser::class);
        $analyser->shouldReceive('run')->once();

        $testSuite = m::mock(PHPUnit_Framework_TestSuite::class);

        $listener = new ComplexityAnalysisListener($analyser, true);

        $listener->endTestSuite($testSuite);
    }
}