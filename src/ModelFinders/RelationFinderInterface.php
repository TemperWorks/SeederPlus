<?php


namespace Temper\SeederPlus\ModelFinders;


use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;

interface RelationFinderInterface
{
    public function find(Model $model, Command $command): Model;

}
