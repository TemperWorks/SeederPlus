<?php


namespace Temper\SeederPlus\console;


use Illuminate\Console\Command;
use PhpSchool\CliMenu\Builder\CliMenuBuilder;
use PhpSchool\CliMenu\MenuItem\MenuMenuItem;
use Temper\SeederPlus\MenuFactory;
use Temper\SeederPlus\SeederFactory;

class SeedCommand extends Command
{
    protected $signature = 'seed';

    protected $description = 'Seed plus';

    protected $seeder;
    public $menuBuilder;
    public $menu;

    public function __construct(SeederFactory $seeder)
    {
        parent::__construct();
        $this->seeder = $seeder;
    }


    public function handle()
    {
        $this->seeder->setContainer($this->laravel);
        $this->seeder->setCommand($this);

        $this->menuBuilder = (new CliMenuBuilder)
            ->setTitle('Seeders menu');


        MenuFactory::renderMenuForSeeders(
            $this->menuBuilder,
            $this->seeder->getAvailableSeeders($this->getPaths())
        );

        $this->menu = $this->menuBuilder->addLineBreak('-')
            ->setWidth(70)
            ->setMarginAuto()
            ->build();

        $this->menu->open();

    }

    private function getPaths(): array
    {
        return [
            $this->laravel->databasePath().DIRECTORY_SEPARATOR.'seeds'
        ];
    }

    public function closeMenu(): void
    {
        $this->closeSubMenu($this->menu);
        $this->menu->close();
    }

    private function closeSubMenu($menu)
    {
        if($menu->getSelectedItem() instanceof MenuMenuItem){
            $this->closeSubMenu($menu->getSelectedItem()->getSubMenu());
            $menu->getSelectedItem()->getSubMenu()->close();
        }
    }


}
