<?php


namespace Temper\SeederPlus\console;


use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Temper\SeederPlus\Database\Snapshot;
use Temper\SeederPlus\Storage;

class DeleteSnapshotCommand extends Command
{
    protected $signature = 'db:delete-snapshot {name}';

    protected $description = 'Remove the snapshot';
    /**
     * @var Snapshot
     */
    private $snapshot;

    public function __construct(Snapshot $snapshot, Storage $storage)
    {
        parent::__construct();
        $this->snapshot = $snapshot;
        $this->storage = $storage;
    }

    public function handle()
    {
        $storageLocation = $this->snapshot->getPathFromName($this->argument('name'));

        if(!$this->snapshot->exists($storageLocation)){
            $this->error('Snapshot with name ' . ($this->argument('name') ?? $storageLocation) . ' does not exists. Try using `db:snapshots` to list all snapshots');
            return;
        }

        $this->snapshot->delete($storageLocation);

        $last_used = $this->storage->get('last_used');
        if($last_used === $storageLocation){
            $this->storage->delete('last_used');
        }

        $this->info("Snapshot {$this->argument('name')} removed");
    }
}
