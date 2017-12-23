<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class Managedinstalls extends Migration
{
    private $tableName = 'managedinstalls';
    private $tableNameV2 = 'managedinstalls_v2';

    public function up()
    {
        $capsule = new Capsule();
        $migrateData = false;

        if ($capsule::schema()->hasTable($this->tableNameV2)) {
            // Migration already failed before, but didnt finish
            throw new Exception("previous failed migration exists");
        }

        if ($capsule::schema()->hasTable($this->tableName)) {
            $capsule::schema()->rename($this->tableName, $this->tableNameV2);
            $migrateData = true;
        }

        $capsule::schema()->create($this->tableName, function (Blueprint $table) {
            $table->increments('id');

            $table->string('serial_number');
            $table->string('name');
            $table->string('display_name');
            $table->string('version')->nullable();
            $table->integer('size')->nullable();
            $table->integer('installed');
            $table->string('status');
            $table->string('type');

            $table->index('display_name');
            $table->index('name');
            $table->index(['name', 'version']);
            $table->index('serial_number');
            $table->index('status');
            $table->index('type');
            $table->index('version');

            if ($migrateData) {
                $capsule::select("INSERT INTO 
                    $this->tableName
                SELECT
                    id,
                    serial_number,
                    display_name,
                    version,
                    size,
                    installed,
                    status,
                    type
                FROM
                    $this->tableNameV2");
            }
        });
    }
    
    public function down()
    {
        $capsule = new Capsule();
        $capsule::schema()->dropIfExists($this->tableName);
        if ($capsule::schema()->hasTable($this->tableNameV2)) {
            $capsule::schema()->rename($this->tableNameV2, $this->tableName);
        }
    }
}
