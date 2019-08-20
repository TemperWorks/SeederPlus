<?php

namespace Temper\SeederPlus;

use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;

abstract class SeederPlus extends Seeder
{

    protected $section = '';
    protected $states = [];
    protected $name = 'seeder';
    protected $enabled = true;

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
        return $this->name;
    }

    public function available(): bool
    {
        return $this->enabled;
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

    public function selected(): void
    {
        $this->command->closeMenu();
        $this->__invoke();
        $this->command->info("{$this->getDisplayName()} ran successful");
    }
}
