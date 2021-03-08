<?php

namespace Narcisonunez\LaravelScripts\Tests\Commands;

use Narcisonunez\LaravelScripts\Tests\TestCase;

class ScriptRunCommandTest extends TestCase
{
    /** @test */
    public function it_should_run_the_given_script()
    {
        $this->artisan('scripts:run', [
            'name' => 'VerifyScriptRunScript',
        ])
            ->expectsOutput('Your script ran successfully.')
            ->assertExitCode(0);
    }

    /** @test */
    public function it_should_run_the_given_script_with_failed_status()
    {
        $scriptClass = config('scripts.base_path') . "\\VerifyScriptRunScript";
        app()->bind($scriptClass, function () {
            throw new \Exception();
        });
        $this->artisan('scripts:run', [
            'name' => 'VerifyScriptRunScript',
        ])
            ->expectsOutput('There was an error with your script.')
            ->assertExitCode(0);
    }

    /** @test */
    public function it_should_print_not_found_class_error_for_unknown_scripts()
    {
        $this->artisan('scripts:run', [
            'name' => 'ScriptNameThatDoesntExist',
        ])
            ->expectsOutput('Class not found: ' . config('scripts.base_path') . "\\ScriptNameThatDoesntExist")
            ->assertExitCode(0);
    }
}
