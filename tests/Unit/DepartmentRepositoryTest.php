<?php

namespace Tests\Unit;

use App\Models\Department;
use App\Repositories\DepartmentRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;

use Tests\TestCase;

class DepartmentRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected $departmentRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->departmentRepository = new DepartmentRepository(new Department());
    }

    public function test_find_department()
    {
        $department = Department::factory()->create(['department_name' => 'HR Department']);

        $foundDepartment = $this->departmentRepository->find($department->id);

        $this->assertNotNull($foundDepartment);
        $this->assertEquals($department->id, $foundDepartment->id);
        $this->assertEquals('HR Department', $foundDepartment->department_name);
    }

    public function test_create_department()
    {
        $data = ['department_name' => 'Finance Department',"QACoordinatorID" => 1];

        $department = $this->departmentRepository->create($data);

        $this->assertInstanceOf(Department::class, $department);
        $this->assertDatabaseHas('departments', ['department_name' => 'Finance Department']);
    }

    public function test_update_department()
    {

        $department = Department::factory()->create(['department_name' => 'Old Name',"QACoordinatorID" => 1]);

        $updatedDepartment = $this->departmentRepository->update($department->id, ['department_name' => 'New Name',"QACoordinatorID" => 1]);

        $this->assertInstanceOf(Department::class, $updatedDepartment);
        $this->assertEquals('New Name', $updatedDepartment->fresh()->department_name);
        $this->assertDatabaseHas('departments', ['department_name' => 'New Name']);
    }

    public function test_destroy_department()
    {

        $department = Department::factory()->create();

        $result = $this->departmentRepository->destroy($department->id);

        $this->assertTrue($result);
        $this->assertDatabaseMissing('departments', ['id' => $department->id]);
    }
}
