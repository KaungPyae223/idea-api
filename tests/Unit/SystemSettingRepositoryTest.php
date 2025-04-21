<?php

namespace Tests\Unit;

use App\Models\SystemSetting;
use App\Models\User;
use App\Repositories\SystemSettingRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SystemSettingRepositoryTest extends TestCase
{

    use RefreshDatabase;

    protected $systemSettingRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->systemSettingRepository = new SystemSettingRepository();
        $this->actingAs(User::factory()->create());

    }

    public function test_find_system_setting()
    {
        $systemSetting = SystemSetting::factory()->create([
            "academic_year" => "2024-2025"
        ]);

        $foundSetting = $this->systemSettingRepository->find($systemSetting->id);

        $this->assertNotNull($foundSetting);
        $this->assertEquals($systemSetting->id, $foundSetting->id);
    }

    public function test_create_system_setting()
    {

        $data = [
            "academic_year" => "2024-2025",
            "idea_closure_date" => now()->addDays(10),
            "final_closure_date" => now()->addDays(20),
            "status" => true
        ];



        $systemSetting = $this->systemSettingRepository->create($data);

        $this->assertInstanceOf(SystemSetting::class, $systemSetting);
        $this->assertDatabaseHas('system_settings', ['academic_year' => '2024-2025']);
    }

    public function test_update_system_setting()
    {

        $systemSetting = SystemSetting::factory()->create([
            "academic_year" => "2023-2024",
            "idea_closure_date" => now()->addDays(5),
            "final_closure_date" => now()->addDays(15),
        ]);

        $updatedData = [
            "academic_year" => "2024-2025",
            "idea_closure_date" => now()->addDays(10),
            "final_closure_date" => now()->addDays(20),
        ];

        

        $updatedSetting = $this->systemSettingRepository->update($systemSetting->id, $updatedData);

        $this->assertEquals('2024-2025', $updatedSetting->fresh()->academic_year);
        $this->assertDatabaseHas('system_settings', ['academic_year' => '2024-2025']);
    }

    public function test_destroy_system_setting()
    {

        $systemSetting = SystemSetting::factory()->create();



        $this->systemSettingRepository->destroy($systemSetting->id);

        $this->assertDatabaseMissing('system_settings', ['id' => $systemSetting->id]);
    }
}
