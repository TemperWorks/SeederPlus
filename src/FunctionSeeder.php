<?php


namespace Temper\SeederPlus;


use Illuminate\Support\Str;
use phpDocumentor\Reflection\DocBlockFactory;
use ReflectionClass;

class FunctionSeeder extends SeederPlus
{
    protected $name;

    private $parentSeeder;

    private $function;

    public function __construct(SeederPlus $parentSeeder, string $function)
    {

        $this->parentSeeder = $parentSeeder;
        $this->function = $function;

        $this->name = $this->getName();
    }

    public function run(): void
    {
        $functionName = $this->function;
        $this->parentSeeder->$functionName;
    }

    private function getName(): string
    {
        $reflector = new ReflectionClass($this->parentSeeder);
        $docblock = $reflector->getMethod($this->function)->getDocComment();
        if ($docblock) {
            $docblock = DocBlockFactory::createInstance()->create($docblock);
            if($docblock->hasTag('name')){
                return $docblock->getTagsByName('name')[0];
            }
        }
       return Str::snake($this->function);
    }

    public function selected(): void
    {
        $this->parentSeeder->command->closeMenu();

        isset( $this->parentSeeder->container)
                ?  $this->parentSeeder->container->call([$this->parentSeeder, $this->function])
                : $this->parentSeeder->$this->function;
        $this->parentSeeder->command->info("{$this->getDisplayName()} ran successful");
    }
}
