<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use App\Models\User;
use App\Models\Backup;
use Carbon\Carbon;


class BackupControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_creating_backup()
    {
        Storage::fake('local');

        // Create a user and act as this user
        $user = User::factory()->create();
        $this->actingAs($user);

        // Create a fake tarball file
        $file = UploadedFile::fake()->create('backup.tar.gz', 1000); // size in kilobytes

        // Send a post request with this file
        $response = $this->postJson('/api/backups/create', [
            'instance_id' => 'test-instance',
            'backup_file' => $file,
        ]);

        // Assert the response status
        $response->assertStatus(201)
                 ->assertJson([
                     'message' => 'Backup created successfully',
                 ]);

    }

    public function test_failing_creating_backup_no_file()
    {
        // Create a user and act as this user
        $user = User::factory()->create();
        $this->actingAs($user);

        // Create a fake tarball file
        $file = null;

        // Send a post request with this file
        $response = $this->postJson('/api/backups/create', [
            'instance_id' => 'test-instance',
            'backup_file' => $file,
        ]);

        // Assert the response status
        $response->assertStatus(422)
                 ->assertJson([
                     'message' => 'The backup file field is required.',
                 ]);

    }

    public function test_show_latest_backup_for_instance()
    {
        // Create a user and act as this user
        $user = User::factory()->create();
        $this->actingAs($user);

        // Create test instance and backups
        $instanceId = 'test-instance';
        $backup1 = Backup::create([
            'instance_id' => $instanceId,
            'swarm_hash' => 'hash1',
            'status' => 'success',
            'created_at' => Carbon::now()->subMinutes(5),
        ]);

        $backup2 = Backup::create([
            'instance_id' => $instanceId,
            'swarm_hash' => 'hash2',
            'status' => 'success',
        ]);

        // Send a GET request to the showLatest route
        $response = $this->getJson("/api/backups/{$instanceId}/latest");

        // Assert the response status is 200 OK
        $response->assertStatus(200);

        // Assert the response contains the latest backup data
        $response->assertJson([
            'instance_id' => $instanceId,
            'swarm_hash' => $backup2->swarm_hash, // should be the latest backup
            'status' => 'success',

        ]);
    }

    public function test_show_latest_backup_for_instance_not_found()
    {
        // Create and act as a user
        $user = User::factory()->create();
        $this->actingAs($user);

        // Send a GET request to the showLatest route for a non-existent instance
        $response = $this->getJson("/api/backups/nonexistent-instance/latest");

        // Assert the response status is 404 Not Found
        $response->assertStatus(404);
    }
}
