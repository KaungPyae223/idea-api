<?php

namespace Tests\Unit;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use App\Models\Department;
use App\Repositories\UserRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;


class UserRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected $userRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->userRepository = new UserRepository();
        $this->actingAs(User::factory()->create());

    }

    public function test_create_user()
    {
        $department = Department::factory()->create();
        $role = Role::factory()->create();
        $permission = Permission::factory()->create();



        $userData = [
            'name' => 'Test User',
            'photo' => 'photo.jpg',
            'email' => 'test@example.com',
            'department_id' => $department->id,
            'role_id' => (string) $role->id,
            'permissions_id' => (string) $permission->id,
        ];

        $user = $this->userRepository->create($userData);

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals('Test User', $user->name);
        $this->assertEquals('test@example.com', $user->email);
        $this->assertDatabaseHas('users', ['email' => 'test@example.com']);
    }

    public function test_find_user()
    {
        $user = User::factory()->create();
        $foundUser = $this->userRepository->find($user->id);
        $this->assertEquals($user->id, $foundUser->id);
    }

    public function test_update_user()
    {
        $user = User::factory()->create();
        $department = Department::factory()->create();
        $role = Role::factory()->create();
        $permission = Permission::factory()->create();




        $updatedData = [
            'name' => 'Updated User',
            'photo' => 'updated_photo.jpg',
            'email' => 'updated@example.com',
            'department_id' => $department->id,
            'role_id' => (string) $role->id,
            'permissions_id' => (string) $permission->id,
        ];

        $updatedUser = $this->userRepository->update($user->id, $updatedData);

        $this->assertEquals('Updated User', $updatedUser->fresh()->name);
        $this->assertEquals('updated@example.com', $updatedUser->fresh()->email);
        $this->assertDatabaseHas('users', ['email' => 'updated@example.com']);
    }
}
