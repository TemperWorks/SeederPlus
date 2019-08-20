<?php


namespace Temper\SeederPlus;


use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class SeederFactory
{
    private $files;
    private $command;
    private $container;

    public function __construct(Filesystem $files)
    {
        $this->files = $files;
    }

    public function getAvailableSeeders(array $paths): Collection
    {
        return Collection::make($paths)->flatMap(function ($path) {
            return Str::endsWith($path, '.php') ? [$path] : $this->files->glob($path.'/*.php');
        })->filter()->map(function ($file) {
            $class = resolve($this->getSeederName($file));
            return $class->setContainer($this->container)->setCommand($this->command);
        })->filter(function ($seeder) {
            return is_subclass_of($seeder, SeederPlus::class);
        })->sort();

    }

    public function getSeederName($path): string
    {
        return str_replace('.php', '', basename($path));
    }

    public function setContainer($container): void
    {
        $this->container = $container;
    }

    public function setCommand(Command $command): void
    {
        $this->command = $command;
    }
}
