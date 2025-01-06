<?php

namespace App\Console\Commands;

use App\Services\AppleService;
use Illuminate\Console\Command;
use Symfony\Component\Console\Command\Command as CommandAlias;

class ExtractAppleJsonFromLogsCommand extends Command
{
    protected $signature = 'extract:apple-json-from-logs {file : Path to the log file}';

    protected $description = 'Command description';

    public function handle(): int
    {
        $filePath = $this->argument('file');

        if (!file_exists($filePath)) {
            $this->error("File not found: $filePath");
            return 1;
        }

        $content = file_get_contents($filePath);
        $pattern = '/Encoded payload:\s*({.+})/';

        if (preg_match_all($pattern, $content, $matches)) {
            foreach ($matches[1] as $json) {
                try {
                    $decoded = json_decode($json, true);
                    if (isset($decoded['signedPayload'])) {
                        $payload = $this->decodePayload($decoded['signedPayload']);

                        if ($payload['notificationType'] === 'SUBSCRIBED') {
                            dd($payload, $json);
                        }
//                        $this->info(json_encode($payload, JSON_PRETTY_PRINT));
                    } else {
                        $this->warn("No 'signedPayload' found in: $json");
                    }
                } catch (\Exception $e) {
                    $this->error("Error decoding JSON: $json");
                }
            }
        } else {
            $this->warn('No JSON objects found in the log file.');
        }

        return 0;
    }

        private function decodePayload(string $signedPayload): array
    {
        $appleService = new AppleService();

        $payload = $appleService->decode($signedPayload);

        if (isset($payload['data']['signedTransactionInfo'])) {
            $payload['data']['signedTransactionInfo'] = $appleService->decode($payload['data']['signedTransactionInfo']);
        }

        if (isset($payload['data']['signedRenewalInfo'])) {
            $payload['data']['signedRenewalInfo'] = $appleService->decode($payload['data']['signedRenewalInfo']);
        }

        return $payload;
    }
}
