<?php


namespace Temper\SeederPlus;


class SeederNotFound extends SeederPlus
{

    private $param;

    private $state;

    protected $info;

    public function __construct(\Temper\SeederPlus\SeederPlus $param, string $state)
    {
        $this->param = $param;
        $this->state = $state;
        $this->name = $state . ' not found';
        $this->enabled = false;
    }




}
