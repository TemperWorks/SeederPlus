<?php

namespace Temper\SeederPlus;

use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;

abstract class SeederPlus extends Seeder
{

    protected $section = '';
    protected $states = [];
    protected $relations = [];
    protected $name = null;
    protected $enabled = true;
    protected $hideFromMenu = false;

    public function getSection(): string
    {
        return $this->section;
    }

    public function hasStates(): bool
    {
        return count($this->states) > 0;
    }

    public function getDisplayName(): string
    {
        if($this->name === null){
            return get_class($this);
        }
        return $this->name;
    }

    public function available(): bool
    {
        return $this->enabled;
    }

    public function hideFromMenu(): bool
    {
        return $this->hideFromMenu;
    }

    public function isDefaultNeededData(): bool
    {
        return array_key_exists(isDefaultNeededData::class, class_uses($this));
    }

    public function getStates(): Collection
    {
        return collect($this->states)->map(function($state){
            if(class_exists($state) && is_subclass_of($state, SeederPlus::class)){
                $class = resolve($state);
                return $class->setContainer($this->container)->setCommand($this->command);
            }
            if(method_exists($this, $state)){
                return new FunctionSeeder($this, $state);
            }
            return new SeederNotFound($this, $state);
        })->filter();
    }
    public function AllRelations(): array
    {
        return collect($this->relations)->merge(
            $this->getStates()->flatMap(function($seeder){
                return $seeder->allRelations();
            })
        )->toArray();

    }
    public function selected(): void
    {
        $this->command->closeMenu();
        $this->__invoke();
        $this->command->info("{$this->getDisplayName()} ran successful");
    }

    public function relation(string $relation, callable $callback)
    {
        $storage = resolve(Storage::class);
        $relation_id = $storage->get('relation.'.$relation);
        if($relation_id === null){
            return $callback();
        }
        if(!array_key_exists($relation, $this->relations)){
            return $callback();
        }

        $modelClass = resolve($this->relations[$relation]);
        $model = $modelClass->find($relation_id);
        if($model === null){
            $storage->delete('relation'.$relation);
            return $callback();
        }
        return $model;

    }
}
