<?php


namespace Temper\SeederPlus\Database;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class Snapshot
{
    use DatabaseConfig;

    private $files;

    public function __construct(Filesystem $files)
    {
        $this->files = $files;
    }

    public function all(string $storagePath): array
    {
        return collect(
            $this->files->glob($storagePath.'/*.sql')
        )->sortByDesc(function($path){
            return $this->files->lastModified($path);
        })->map(function($path){
            return $this->getNameFromPath($path);
        })->values()->toArray();
    }

    public function make(string $storageLocation): void
    {
        exec("mysqldump -h {$this->getDbHost()} -u {$this->getDbUsername()} --password={$this->getDbPassword()} {$this->getDbName()} > {$storageLocation} 2>/dev/null");
    }

    public function delete(string $storageLocation): void
    {
        $this->files->delete($storageLocation);
    }

    public function restore(string $storageLocation): void
    {

        DB::connection()->getSchemaBuilder()->dropAllTables();
        DB::connection()->getSchemaBuilder()->dropAllViews();

        exec("mysql -h {$this->getDbHost()} -u {$this->getDbUsername()} --password={$this->getDbPassword()} {$this->getDbName()} < {$storageLocation} 2>/dev/null");
    }

    public function exists(string $storageLocation): bool
    {
        return $this->files->exists($storageLocation);
    }

    public function getNameFromPath($path): string
    {
        return str_replace('.sql', '', basename($path));
    }

    public function getPathFromName(string $name): string
    {
        return config('seederplus.snapshot_storage_path') . $this->parseFileName($name) . '.sql';
    }

    public function parseFileName(string $name): string
    {
        return Str::slug($name);
    }


}
