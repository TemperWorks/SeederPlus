<?php


namespace Temper\SeederPlus\Database;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\DB;

class BaseSnapshot
{
    private $snapshot;

    public function __construct(Snapshot $snapshot)
    {
        $this->snapshot = $snapshot;
    }

    public function exists(): bool
    {
        return $this->snapshot->exists($this->getPath());
    }

    public function make(): void
    {
        $this->snapshot->make($this->getPath());
    }

    public function restore(): void
    {
        $this->snapshot->restore($this->getPath());
    }

    private function getPath(): string
    {
        return config('seederplus.base_snapshot');
    }

}
