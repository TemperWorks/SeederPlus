<?php


namespace Temper\SeederPlus\console;


use Illuminate\Console\Command;
use PhpSchool\CliMenu\Builder\CliMenuBuilder;
use PhpSchool\CliMenu\MenuItem\MenuMenuItem;
use Temper\SeederPlus\MenuFactory;
use Temper\SeederPlus\SeederFactory;
use Temper\SeederPlus\SeederPlus;
use Temper\SeederPlus\Storage;

class SetupRelationCommand extends Command
{
    protected $signature = 'seed:relation';

    protected $description = 'Setup a relation to be used by the seeders';

    protected $seeder;
    protected $storage;

    public function __construct(SeederFactory $seeder, Storage $storage)
    {
        parent::__construct();
        $this->seeder = $seeder;
        $this->storage = $storage;
    }


    public function handle()
    {
        $this->seeder->setContainer($this->laravel);
        $this->seeder->setCommand($this);

        $seeders = $this->seeder->getAvailableSeeders($this->getPaths());

        $relations = $seeders->flatMap(function(SeederPlus $seeder){
            return $seeder->AllRelations();
        });

        $relation = $this->choice(
            'Relation to set:',
            $relations->map(function($class, $relationName){
                return $relationName . ' (' . $class . ')';
            })->toArray()
        );

        if(collect(config('seederplus.relation_finders'))->count() > 1)
        {
            $finder = $this->choice(
                "How to fetch {$relation} relation?",
                collect(config('seederplus.relation_finders'))->values()->toArray()
            );
        }
        else{
            $finder = collect(config('seederplus.relation_finders'))->first();
        }

        $finder = resolve(collect(config('seederplus.relation_finders'))->search($finder));
        $model = $finder->find(resolve($relations[$relation]), $this);

        $this->storage->put('relation.'.$relation, $model->getKey());
        $this->info("relation {$relation} is now using model with key {$model->getKey()}");
    }

    private function getPaths(): array
    {
        return [
            $this->laravel->databasePath().DIRECTORY_SEPARATOR.'seeds'
        ];
    }
}
