# Seeders on steroids

SeederPlus improves the seeder-driven workflow. 
It enables to easily & fast seed or reset a database to a certain state.

![Image of seeder menu](https://s3.amazonaws.com/profile_photos/1137302407321018.hSL4BH8Ga3Vjgu2MQDgb_height640.png?X-Amz-Security-Token=AgoJb3JpZ2luX2VjELP%2F%2F%2F%2F%2F%2F%2F%2F%2F%2FwEaCXVzLWVhc3QtMSJIMEYCIQCFKkU92Ri%2BXAcNIhTbME%2FQmuHAPv3%2FgNrK7Ant70NA%2BAIhAP4Dhuz%2FuG1Z%2Bwv5STBTRNLqM87yNyDCkY%2FDlMCxhqbZKtoDCFsQABoMNDAzNDgzNDQ2ODQwIgy6i0UfoWMUgJs%2FbtkqtwOdIHwNcGosFTujcY50k0%2FHZUsbJiYhqVOoFHnAmiAypn47AzvyrW9CaleWo23GJY%2FTNVHLgnFEqATKrDPFiEVI7uX3p7clglXKk0ZVaMuc8wN8jKUlFFyYsJJHAm6Kiif0jRhN0cf5k9KZzQSVqFZ1i61uqrFWgytLA2m6gSBdIgxB59P4yUt2rGyPXRPGZ1RYCUg%2BeNizZZSgzOIiUSm2n8HGSt%2FzNhy7xRORRozbHqAWT1n78IBD2%2BAEWv%2BzhCC6eRghLEGS5As997PNNBP74ayR2%2BsJLg1VmiBumktrq4ZNDyibQFEI%2FsEIkQUNbkLa%2F%2BYn7af1Ow9fnkE2Xok0njjTqPh%2FT4LcBZD9U9jOImGRs3Io1koTVprStfo8yRRUbzWFY9kIVz629TRIyi4%2F3LVM%2B%2BnV%2BThkL3o1ecqxgLZ0p%2FjVAdjbbIy9A15brqYGxHtKHMzikWsQZ8MVqsy8Iu4FhOGl1fRydcDBmVl8nESc62SB9nja7xMT1SHYIb8XF06OV3Q4GKv0dhYAdsAw3JRzBWwN5d5sjUWd1vGPwF8H0s7xeGs3GrmyjYYmzQuAzIFbP%2F3iMOqDlOsFOrMBB7uxEZqG%2FLgDTIHOpIiyE%2FRACAfdbaZ9qi7Ha5d4MvH3C30u6ksx%2BEfJI8FjAWwVozdDrhVQNEGu59rhqqyIYKcwZ02ncN%2B%2FzXStrvix04JQH3y%2FytgR0bR37MA3gSICh6PJkhZibiGKXx2r2a0YhCV9kv67L%2FLb1l6RYEiSNOpQzY7rZc878tQ5APa3Al1IuYoqOxZMBl6fcMFz9cIWsrt9e6piT7H6uzE9jsmsWISW27A%3D&X-Amz-Algorithm=AWS4-HMAC-SHA256&X-Amz-Date=20190827T111646Z&X-Amz-SignedHeaders=host&X-Amz-Expires=1800&X-Amz-Credential=ASIAV34L4ZY4JWWZIZQM%2F20190827%2Fus-east-1%2Fs3%2Faws4_request&X-Amz-Signature=6df02afcea45eef0e0fc9c7ea95e09cc124d717496210d7b2a32da660167ddaa#_=_)

## Installation

[todo] publish maybe? 

You can install the package via composer:

```bash
composer require Temper/seederplus
```

## Usage

### Database setup
When dealing with a lot of migrations in large projects doing a `php artisan migrate:fresh` command might take a while. 
This package speeds this up, by making and storing a snapshot of the migrated database. This snapshot can then be restored without running all migrations again. 

To setup the db, run:  
``` bash
php artisan db:fresh
```
> caution! This will drop your current database. 

To reset the database to that fresh state,  simply run the same command again.

To force rebuilding of the snapshot, run:
``` bash
php artisan db:fresh --build
```

### Snapshots 
When your database is in a certain state, it can be helpfull to take a snapshot. When you want to go back to that specific state you can then reset the snaphot. 

#### Make a snapshot
To make a snapshot, run: 
``` bash
php artisan db:snap "snapshot name"
```
#### Restore a snapshot
To restore to your latest used/generated snapshot, run: 
``` bash
php artisan db:reset
```

To restore a specific snapshot, you can pass the name to the command. 
``` bash
php artisan db:reset "snapshot name"
```

#### List snapshots
You can list all your snapshots, and run or remove them by using
``` bash
php artisan db:snapshots
```

### Seeders

You can run a seeder by running the `php artisan seed` command, from this command you can select a seeder to run. 

To have a seeder appear in the menu, simply make it extend the SeederPlus class instead of the normal laravel Seeder class.  
A seeder can have a name and a section. Seeders are grouped by their section,  the name is displayed when listing. 

Example:
``` php 
<?php

use App\User;
use Temper\SeederPlus\SeederPlus;

class UsersSeeder extends SeederPlus
{

    protected $name = 'Users';

    protected $section = '1 - Most used';

    public $states = [
        AdminUserSeeder::class,
        'createTesterUser'
    ];

    public function run(): void
    {
        // Default
    }
    
    /**
     * @name Create a test user
     */
    public function createTesterUser(): void
    {
        // state seeder
    }
}
```

#### States
A seeder can have multiple states, for example a post seeder can have a published and unpublished post. 
A state can be defined as another seeder or as a function in the same class.

##### seeder class
Simply define the state in the states array. The class needs to extends the SeederPlus class as well. 

```php 
 public $states = [
    AdminUserSeeder::class,
];
```

##### function 
Its also possible to define functions for different states.
simply have function name in the states array. To define a name for the state, you can add a @name to the docblock. Otherwise just the function name will be used. 
  
``` php 
public $states = [
    'createTesterUser'
];

/**
 * @name Create a test user
 */
public function createTesterUser(): void
{
    // state seeder
}
```

### Relations

When a model has relationships it might be useful to use a already existing record instead of creating a new one.
To allow this, its possible to define what relationships the seeder might need. 

You can set a specific record to be used for a specific relation. To do this, run the relation command. 
``` bash
php artisan seed:relation
```
It is possible to configure other "resolvers" than just finding a record by id. For example when using hashIds, a implementation for the RelationFinderInterface could be made and could be added to the config `relation_finders`.
  
  #### using relations
  ```php
  public $relations = [
      'publisher' => User::class
  ];
  
  public function run()
  {
      $publisher = $this->relation('publisher', function() {
          return factory(User::class)->create();
      });
  }
  ```
  After defining the relation in the relations array using a name and the class you can use them in your seeder. 
  The `relation` function returns the relation model when the relation is configured, when the relation is not configured it uses the callback. 
