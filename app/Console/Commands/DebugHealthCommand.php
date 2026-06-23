<?php

namespace App\Console\Commands;

use App\Domain\Health\Actions\GetHealthDebugData;
use Illuminate\Console\Command;

class DebugHealthCommand extends Command
{
    protected $signature = 'debug:health {--days=7 : Show issues from the last X days}';

    protected $description = 'Display the application health report.';

    public function __construct(
        private readonly GetHealthDebugData $getHealthDebugData,
    ) {
        parent::__construct();
    }

    public function handle(): int
    {
        $days = $this->option('days');

        $events = $this->getHealthDebugData->execute(
            $days !== null ? (int) $days : null,
        );

        if ($events->isEmpty()) {
            $this->info('No health events found.');

            return self::SUCCESS;
        }

        $this->table([
            'Source',
            'Code',
            'Exception',
            'Occurrences',
            'First Seen',
            'Last Seen',
        ], $events->map(fn($event) => [
            $event->source->value,
            $event->code,
            $event->exception,
            $event->occurrences,
            $event->first_seen_at,
            $event->last_seen_at,
        ]));

        foreach ($events as $event) {
            if (empty($event->context)) {
                continue;
            }

            $this->newLine();

            $this->info(sprintf(
                '[%s] %s',
                $event->source->value,
                $event->code,
            ));

            foreach ($event->context as $key => $value) {
                if (! is_scalar($value)) {
                    $value = json_encode($value, JSON_PRETTY_PRINT);
                }

                $value = str_replace(
                    PHP_EOL,
                    PHP_EOL . '    ',
                    (string) $value,
                );

                $this->line(sprintf(
                    '  <comment>%s:</comment> %s',
                    ucfirst(str_replace('_', ' ', $key)),
                    $value,
                ));
            }
        }

        return self::SUCCESS;
    }
}
