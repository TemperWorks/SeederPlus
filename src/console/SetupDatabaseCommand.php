<?php


namespace Temper\SeederPlus\console;


use Illuminate\Console\Command;
use Temper\SeederPlus\Database\BaseSnapshot;
use Temper\SeederPlus\SeederFactory;
use Temper\SeederPlus\SeederPlus;
use Temper\SeederPlus\Storage;

class SetupDatabaseCommand extends Command
{
    protected $signature = 'db:fresh {--b|build}';

    protected $description = 'Setup a empty database with only the needed seeds executed for your seeding workflow';

    private $baseSnapshot;

    private $seeder;

    public function __construct(BaseSnapshot $baseSnapshot, Storage $storage, SeederFactory $seeder)
    {
        parent::__construct();
        $this->baseSnapshot = $baseSnapshot;
        $this->storage = $storage;
        $this->seeder = $seeder;
    }

    public function handle()
    {
        $this->info('Setting db to the minimal state');
        $this->seeder->setContainer($this->laravel);
        $this->seeder->setCommand($this);

        if (!$this->baseSnapshot->exists() || $this->option('build')) {

            $this->info('Base snapshot does not exists yet, creating one now');

            $this->call('migrate:fresh');

            $this->seeder->getAvailableSeeders($this->getPaths())
                ->filter(function(SeederPlus $seeder) {
                    return $seeder->isDefaultNeededData();
                })->each(function($seeder){
                    $this->info("Running {$seeder->getDisplayName()}");
                    $seeder->__invoke();
                });

            $this->baseSnapshot->make();

            $this->info('All set, your good to go 🚀');
            return;
        }

        $this->baseSnapshot->restore();

        $this->info('Base db ready, lets go 🚀');
    }

    private function getPaths(): array
    {
        return [
            $this->laravel->databasePath().DIRECTORY_SEPARATOR.'seeds'
        ];
    }
}
