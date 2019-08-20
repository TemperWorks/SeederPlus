<?php


namespace Temper\SeederPlus;


use Illuminate\Filesystem\Filesystem;

class Storage
{
    private $files;
    private $location;

    public function __construct(Filesystem $files)
    {
        $this->files = $files;
        $this->location = config('seederplus.storage_file');
    }

    public function put(string $key, $value)
    {
        $contents = $this->getContents();
        $contents[$key] = $value;
        $this->write($contents);
    }

    public function get(string $key)
    {
        $contents = $this->getContents();
        return $contents[$key] ?? null;
    }

    public function delete(string $key)
    {
        $this->put($key, null);
    }

    private function getContents(): array
    {
        if(!$this->files->exists($this->location)){
            $this->write([]);
        }
        return $this->read();

    }

    private function write(array $contents): void
    {
        $this->files->replace($this->location, json_encode($contents));
    }

    private function read(): array
    {
        return json_decode($this->files->get($this->location), true);
    }

}
