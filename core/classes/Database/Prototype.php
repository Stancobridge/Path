<?php
/**
 * @Author by Sulaiman Adewale.
 * @Date 12/5/2018
 * @Time 10:32 PM
 * @Project Path
 */

namespace Path\Database;

import(
    "core/classes/Database/Structure",
    "core/classes/Database/Connection"
);

use Path\Database\Connection\Mysql;
use Path\Database\Structure;
use Path\DataStructureException;

class Prototype
{
    private $db_conn;
    protected $table_name = null;
    protected $primary_key = null;
    public function __construct()
    {
        $this->db_conn = Mysql::connection();
    }
    public function create(string $table,Table $table_instance){
        $proto = new Structure($table);
        $proto->action = "creating";
        $primary_key = $table_instance->primary_key ?? "id";

        $proto->column($primary_key)
            ->type("INT")
            ->primaryKey()
            ->increments();

        $table_instance->install($proto);

//        Add extra setup column
        $proto->column("is_deleted")
            ->type("boolean")
            ->default(0);

        $proto->column("date_added")
            ->type("int")
            ->nullable();

        $proto->column("last_update_date")
            ->type("int")
            ->nullable();

        $proto->executeQuery();
        return $proto;
    }
    public function alter(string $table,Table $table_instance){
        $proto = new Structure($table);
        $proto->action = "altering";
        $table_instance->update($proto);

        $proto->executeQuery();
        return $proto;
    }

    public function drop(...$tables){
        $tables = join(",",$tables);
        try{
            $query = $this->db_conn->query("DROP TABLE IF EXISTS {$tables}");
        }catch (\Exception $e){
            throw new DataStructureException($e->getMessage());
        }
        return $this;
    }

    /**
     * @param $table
     * @return $this
     * @throws DataStructureException
     */
    public function truncate($table){
        try{
            $query = $this->db_conn->query("TRUNCATE TABLE `{$table}`");
        }catch (\Exception $e){
            throw new DataStructureException($e->getMessage());
        }
        return $this;
    }
}