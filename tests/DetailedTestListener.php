<?php

use PHPUnit\Framework\Test;
use PHPUnit\Framework\TestListener;
use PHPUnit\Framework\TestListenerDefaultImplementation;
use PHPUnit\Framework\TestSuite;
use PHPUnit\Framework\Warning;
use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\TestFailure;

class DetailedTestListener implements TestListener
{
    use TestListenerDefaultImplementation;

    public function startTest(Test $test): void
    {
        printf("Starting test '%s'\n", $test->getName());
    }

    public function endTest(Test $test, float $time): void
    {
        printf("Finished test '%s' in %f seconds\n", $test->getName(), $time);
    }

    public function addError(Test $test, \Throwable $t, float $time): void
    {
        printf("Error in test '%s': %s\n", $test->getName(), $t->getMessage());
    }

    public function addWarning(Test $test, Warning $e, float $time): void
    {
        printf("Warning in test '%s': %s\n", $test->getName(), $e->getMessage());
    }

    public function addFailure(Test $test, AssertionFailedError $e, float $time): void
    {
        printf("Failure in test '%s': %s\n", $test->getName(), $e->getMessage());
    }

    public function addIncompleteTest(Test $test, \Throwable $t, float $time): void
    {
        printf("Incomplete test '%s'\n", $test->getName());
    }

    public function addRiskyTest(Test $test, \Throwable $t, float $time): void
    {
        printf("Risky test '%s'\n", $test->getName());
    }

    public function addSkippedTest(Test $test, \Throwable $t, float $time): void
    {
        printf("Skipped test '%s'\n", $test->getName());
    }
}
