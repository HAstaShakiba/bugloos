<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use App\Models\Log;

class StoreLogs extends Command
{
    public const INSERT_CHUNK_SIZE = 5;

    protected $signature = 'store:log';

    protected $description = 'store logs data into database';

    public function handle()
    {
        $handle = Storage::disk('local')->readStream('logs.txt');

        $logs = [];
        $count = 0;

        if ($handle) {
            while (!feof($handle)) {
                $line = trim(fgets($handle), "\n\r");
                if (empty($line)) {
                    continue;
                }

                [$service, $_, $dataTimeString, $method, $route, $protocol, $status] = explode(" ", $line);

                // remove / form start route name
                $route = str_replace('/', ' ', $route);

                //[17/Sep/2022:10:33:59] => 17/Sep/2022 10:33:59 => Carbon
                preg_match_all("/\[(.*?)\]/", $dataTimeString, $matches);
                $dataTimeString = preg_replace('/:/', ' ', $matches[1][0], 1);
                $called_at = Carbon::createFromFormat("j/M/Y h:i:s", $dataTimeString);

                $count++;
                if ($count <= self::INSERT_CHUNK_SIZE) {
                    $logs[] = compact('service', 'method', 'route', 'protocol', 'status', 'called_at');
                }

                if ($count === self::INSERT_CHUNK_SIZE) {
                    Log::query()->upsert($logs, ['method', 'route', 'status', 'called_at'], ['service', 'protocol']);
                    $logs = [];
                    $count = 0;
                }
            }
        }


        $this->output->info('Inset Compiled.');

        return Command::SUCCESS;
    }
}
