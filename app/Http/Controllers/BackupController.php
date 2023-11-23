<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Backup;

function doStuffWithAnsibleToGetSwarmHash($backupfile) {
    return Hash::make($backupfile);
}

class BackupController extends Controller
{
    public function store(Request $request)
    {
        // Validate the request...
        $validatedData = $request->validate([
            'instance_id' => 'required|string|max:255',
            'backup_file' => 'required|file|mimes:tar,gz', // Validate the file
        ]);

        // Handle file upload
        if (!$request->hasFile('backup_file') || !$request->file('backup_file')->isValid()) {
            return response()->json(['message' => 'Invalid file upload'], 400);
        }

        // Create a new Backup record
        // TODO: Via background worker that calls ansible...
        $swarmHash = doStuffWithAnsibleToGetSwarmHash($request->file('backup_file'));

        // Create a backup record
        $backup = new Backup();
        $backup->instance_id = $request->instance_id;
        $backup->swarm_hash = $swarmHash;
        $backup->status = 'success';

        return response()->json([
            'message' => 'Backup created successfully',
            'swarm_hash' => $swarmHash
        ], 201);
    }

    public function showLatest($instanceId)
    {
        $latestBackup = Backup::where('instance_id', $instanceId)->latest()->first();

        // Check if a backup was found
        if (!$latestBackup) {
            return response()->json(['message' => 'No backup found for the specified instance.'], 404);
        }

        return response()->json($latestBackup);

    }

    public function index($instanceId)
    {
        // List all backups for a specific instance...
        $backups = Backup::where('instance_id', $instanceId)->latest()->paginate(20);

        // Check if a backup was found
        if (!$backups) {
            return response()->json(['message' => 'No backups found for the specified instance.'], 404);
        }

        return response()->json($backups);
    }

    public function showByDate($instanceId, $date)
    {
        // Retrieve a backup for a specific date...

    }

    public function forget($instanceId, $backupId)
    {
        // Delete a specific backup...
    }
}
