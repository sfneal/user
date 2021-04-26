<?php


namespace Sfneal\Users\Tests\Feature;


use Sfneal\Testing\Utils\Traits\ModelAttributeAssertions;
use Sfneal\Users\Models\Role;
use Sfneal\Users\Models\Team;
use Sfneal\Users\Models\User;
use Sfneal\Users\Tests\TestCase;

class MigrationsTest extends TestCase
{
    use ModelAttributeAssertions;

    /** @test */
    public function role_table_is_accessible()
    {
        $data = [
            'type' => 'user',
            'name' => 'Employee',
            'description' => "Here's an example description",
            'order' => rand(1, 10),
        ];
        $createdModel = Role::query()->create($data);
        $foundModel = Role::query()->find($createdModel->getKey());

        $this->modelAttributeAssertions($data, $foundModel);
    }

    /** @test */
    public function team_table_is_accessible()
    {
        $data = [
            'user_id' => rand(1, 999),
            'order' => rand(1, 10),
        ];
        $createdModel = Team::query()->create($data);
        $foundModel = Team::query()->find($createdModel->getKey());

        $this->modelAttributeAssertions($data, $foundModel);
    }

    /** @test */
    public function user_table_is_accessible()
    {
        $data = [
            'role_id' => rand(1, 5),
            'first_name' => 'Patrice',
            'last_name' => 'Bergeron',
            'nickname' => 'Bergy',
            'title' => 'Captain',
            'email' => 'patrice@bruins.com',
            'rate' => 200,
            'phone_mobile' => '508-555-6516',
            'username' => 'pbergeron',
            'password' => 'HabsStink1234!',
            'status' => 1,
        ];
        $createdModel = User::query()->create($data);
        $foundModel = User::query()->find($createdModel->getKey());

        unset($data['password']);

        $this->modelAttributeAssertions($data, $foundModel, 'assertSame');
    }
}
