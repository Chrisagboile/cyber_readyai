<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use OpenAI\Laravel\Facades\OpenAI;

class TestOpenAI extends Command
{
    protected $signature = 'ai:test';

    protected $description = 'Test the OpenAI API connection';

    public function handle(): int
    {
        $this->info('Testing OpenAI connection...');

        try {
            $response = OpenAI::responses()->create([
                'model' => 'gpt-5.6-luna',
                'input' =>
                    'Reply with exactly: '
                    . 'CyberReadyAI connection successful.',
            ]);

            $this->newLine();

            $this->info($response->outputText);

            return self::SUCCESS;
        } catch (\Throwable $exception) {
            $this->error('OpenAI connection failed.');

            $this->error(
                $exception->getMessage()
            );

            return self::FAILURE;
        }
    }
}
