<?php


use Phinx\Seed\AbstractSeed;

class UsersTableSeeder extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * http://docs.phinx.org/en/latest/seeding.html
     */
    public function run()
    {
        $data = [
            [
                'username' => 'admin',
                'password' => password_hash('123', PASSWORD_BCRYPT)
            ]
        ];

        $posts = $this->table('users');
        $posts->insert($data)
            ->save();
    }
}
