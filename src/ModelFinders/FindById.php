<?php


namespace Temper\SeederPlus\ModelFinders;


use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;

class FindById implements RelationFinderInterface
{
    public function find(Model $model, Command $command): Model
    {
        $id = $command->ask('Model id?');
        return $model->findOrFail($id);
    }
}
