<?php


use App\Models\Task;
use Phinx\Migration\AbstractMigration;

class CreateTasksTable extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    addCustomColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Any other destructive changes will result in an error when trying to
     * rollback the migration.
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change()
    {
        $table = $this->table('tasks');
        $table->addColumn('name', 'string', ['limit' => 100])
            ->addColumn('email', 'string',  ['limit' => 100])
            ->addColumn('text', 'string', ['limit' => 500])
            ->addColumn('status', 'enum', ['values' => [Task::DONE, Task::IN_PROGRESS], 'default' => (string) Task::IN_PROGRESS])
            ->addColumn('is_edited', 'boolean')
            ->addColumn('created_at', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('updated_at', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
            ->create();
    }
}
