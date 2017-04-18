<?php

namespace DrawMyAttention\CodeQuality\Tests\Unit;

use PHPUnit\Framework\TestCase;
use DrawMyAttention\CodeQuality\Listeners\ComplexityAnalysisListener;

class ComplexityAnalysisListenerTest extends TestCase
{
    /** @test */
    public function it_can_be_instantiated()
    {
        $this->assertInstanceOf(ComplexityAnalysisListener::class, new ComplexityAnalysisListener);
    }
}