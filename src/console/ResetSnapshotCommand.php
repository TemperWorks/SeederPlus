<?php


namespace Temper\SeederPlus\console;


use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Temper\SeederPlus\Database\Snapshot;
use Temper\SeederPlus\Storage;

class ResetSnapshotCommand extends Command
{
    protected $signature = 'db:reset {name?}';

    protected $description = 'Reset the snapshot from your db. If no name is passed, the last used snapshot will be used.';
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
        if ($this->argument('name') !== null) {
            $storageLocation = $this->snapshot->getPathFromName($this->argument('name'));
        }
        else{
            $storageLocation = $this->storage->get('last_used');
            if($storageLocation === null){
                $this->error('No last used snapshot found');
                return;
            }
        }

        if(!$this->snapshot->exists($storageLocation)){
            $this->error('Snapshot with name ' . ($this->argument('name') ?? $storageLocation) . ' does not exists. Try using `db:snapshots` to list all snapshots');
            return;
        }

        $this->snapshot->restore($storageLocation);

        $this->info('snapshot reset completed, running migrations');

        $this->call('migrate');

        $this->storage->put('last_used', $storageLocation);
    }
}
