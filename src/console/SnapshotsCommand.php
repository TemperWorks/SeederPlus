<?php


namespace Temper\SeederPlus\console;


use Illuminate\Console\Command;
use Temper\SeederPlus\Database\Snapshot;

class SnapshotsCommand extends Command
{
    protected $signature = 'db:snapshots';

    protected $description = 'List all available snapshots';
    /**
     * @var Snapshot
     */
    private $snapshot;

    public const ACTIONS = [
        'use' => 'Use this snapshot ✅',
        'delete' => 'Delete this snapshot 🗑️',
        'cancel' => 'Do nothing'
    ];

    public function __construct(Snapshot $snapshot)
    {
        parent::__construct();
        $this->snapshot = $snapshot;
    }

    public function handle()
    {
        $snapshot = $this->choice(
            'Select snapshot to run or delete',
            $this->snapshot->all(config('seederplus.snapshot_storage_path'))
        );

        $this->info("Selected {$snapshot}");

        $choice = $this->choice(
          'I want to:',
          self::ACTIONS
        );

        $this->$choice($snapshot);
    }

    public function use(string $snapshot): void
    {
        $this->call('db:reset', [
            'name' => $snapshot
        ]);
    }

    public function delete(string $snapshot): void
    {
        $this->call('db:delete-snapshot', [
            'name' => $snapshot
        ]);
    }

    public function cancel(string $snapshot): void
    {
        $this->info('Sure, bye 👋');
    }
}
