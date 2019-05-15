<?php
namespace App\Migration;

use Illuminate\Database\Capsule\Manager as Capsule;
use Phinx\Migration\AbstractMigration;

class Migration extends AbstractMigration
{
    public $capsule;

    public $schema;

    protected function init()
    {
        $data = require dirname(__DIR__) . '../../src/settings.php';

        $this->capsule = new Capsule();
        $this->capsule->addConnection($data['settings']['db']);

        $this->capsule->bootEloquent();
        $this->capsule->setAsGlobal();
        $this->schema = $this->capsule->schema();
    }
}
