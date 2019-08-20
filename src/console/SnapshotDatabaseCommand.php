<?php


namespace Temper\SeederPlus\console;


use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Temper\SeederPlus\Database\Snapshot;
use Temper\SeederPlus\Storage;

class SnapshotDatabaseCommand extends Command
{
    protected $signature = 'db:snap {name?}';

    protected $description = 'Make a snapshot of your db';
    /**
     * @var Snapshot
     */
    private $snapshot;
    private $storage;

    public function __construct(Snapshot $snapshot, Storage $storage)
    {
        parent::__construct();
        $this->snapshot = $snapshot;
        $this->storage = $storage;
    }

    public function handle()
    {
        $storageLocation = $this->snapshot->getPathFromName($this->argument('name') ?? $this->createFileName());

        $this->snapshot->make($storageLocation);

        $this->info("Snapshot with name {$this->snapshot->getNameFromPath($storageLocation)} created");

        $this->storage->put('last_used', $storageLocation);

    }

    public function createFileName(): string
    {
        return now()->toISOString() . '-' . Str::random(3);
    }
}
