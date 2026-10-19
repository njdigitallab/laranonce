<?php

namespace NjDigitalLab\Laranonce\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use NjDigitalLab\Laranonce\Models\Nonce;
use NjDigitalLab\Laranonce\Laranonce;
use function Laravel\Prompts\password;
use function Laravel\Prompts\text;

class PruneNoncesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'laranonce:prune {option=expired}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Prune nonces from storage.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $option = $this->argument('option');

        if (! in_array($option, ['expired', 'all'])) {
            return $this->error('Invalid option supplied to command. Only `expired` or `all` accepted.');
        }

        $this->comment('Beginning pruning process... please wait...');

        $this->comment($this->prune($option).' deleted from database...');

        $this->info('Nonces pruned successfully!');
    }

    /**
     * Clean up the database.
     */
    protected function prune(string $option): int
    {
        if ($option == 'all') {
            return Nonce::getQuery()->delete();
        }

        $date = date('Y-m-d H:i:s', now()->timestamp - Laranonce::lifetime());

        return Nonce::where('updated_at', '<', $date)->delete();
    }
}


