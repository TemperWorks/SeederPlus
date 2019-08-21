<?php


namespace Temper\SeederPlus\Database;


trait DatabaseConfig
{
    /**
     * Get the database connection from the config settings.
     *
     * @return \Illuminate\Config\Repository|mixed
     */
    protected function getDatabaseConnection()
    {
        return config('database.default');
    }

    /**
     * Get the Database host from the config settings.
     *
     * @return \Illuminate\Config\Repository|mixed
     */
    protected function getDbHost()
    {
        $connection = $this->getDatabaseConnection();

        return config("database.connections.{$connection}.host") ?? config("database.connections.{$connection}.write.host");
    }

    /**
     * Get the Database username from the config settings.
     *
     * @return \Illuminate\Config\Repository|mixed
     */
    protected function getDbUsername()
    {
        $connection = $this->getDatabaseConnection();

        return config("database.connections.{$connection}.username");
    }

    /**
     * Get the database password from the config settings.
     *
     * @return \Illuminate\Config\Repository|mixed
     */
    protected function getDbPassword()
    {
        $connection = $this->getDatabaseConnection();

        return config("database.connections.{$connection}.password");
    }

    /**
     * Get the name of the database from config settings.
     *
     * @return \Illuminate\Config\Repository|mixed
     */
    protected function getDbName()
    {
        $connection = $this->getDatabaseConnection();

        return config("database.connections.{$connection}.database");
    }
}
