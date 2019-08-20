<?php


namespace Temper\SeederPlus;


use Illuminate\Support\Collection;
use PhpSchool\CliMenu\Builder\CliMenuBuilder;
use PhpSchool\CliMenu\CliMenu;

class MenuFactory
{
    public static function renderMenuForSeeders(
        CliMenuBuilder $menuBuilder,
        Collection $seeders
    ) {
        $seedersGrouped = $seeders
            ->groupBy(function(SeederPlus $seeder){
                return $seeder->getSection();
            });

        $seedersGrouped->each(function($seederGroup, $group) use ($menuBuilder){
            $menuBuilder->addStaticItem($group);
            $seederGroup->each(function(SeederPlus $seeder) use ($menuBuilder){
                if ($seeder->hasStates()) {
                    $menuBuilder->addSubMenu($seeder->getDisplayName(), function (CliMenuBuilder $builder) use ($seeder) {
                        $builder->setTitle($seeder->getDisplayName());
                        $builder->addItem('Default', [$seeder, 'selected']);
                        MenuFactory::renderMenuForSeeders($builder, $seeder->getStates());
                    });
                    return;
                }
                $menuBuilder->addItem($seeder->getDisplayName(), [$seeder, 'selected'], false, !$seeder->available());

            });
            $menuBuilder->addLineBreak();
        });
    }
}
