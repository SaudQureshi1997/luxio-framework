<?php

namespace Luxio\Utils;

use Config;
use Swoole\Coroutine\MySQL;

class DB
{
    protected $connection;

    public function __construct()
    {
        $default = Config::get('database.default');
        $configs = Config::get("database.connections.$default");

        $this->initialize($default, $configs);
    }

    protected function initialize(string $connection_type, array $configs)
    {
        switch ($connection_type) {
            case 'mysql':
                $this->connection = new MySQL($configs);
                break;

            default:
                $this->connection = new MySQL($configs);
                break;
        }
    }
}
