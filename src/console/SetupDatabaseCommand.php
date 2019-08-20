<?php


namespace Temper\SeederPlus\console;


use Illuminate\Console\Command;
use Temper\SeederPlus\Database\BaseSnapshot;
use Temper\SeederPlus\Storage;

class SetupDatabaseCommand extends Command
{
    protected $signature = 'seed:initDb';

    protected $description = 'Setup a empty database with only the needed seeds executed for your seeding workflow';

    private $baseSnapshot;

    public function __construct(BaseSnapshot $baseSnapshot, Storage $storage)
    {
        parent::__construct();
        $this->baseSnapshot = $baseSnapshot;
        $this->storage = $storage;
    }

    public function handle()
    {
        $this->info('Setting db to the minimal state');

        if (!$this->baseSnapshot->exists()) {

            $this->info('Base snapshot does not exists yet, creating one now');

            $this->call('migrate:fresh');

            // [todo] Run seeder?

            $this->baseSnapshot->make();

            $this->info('All set, your good to go 🚀');
            return;
        }

        $this->baseSnapshot->restore();

        $this->info('Base db ready, lets go 🚀');
    }
}
