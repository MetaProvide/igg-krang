<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Backup extends Model
{
  use HasFactory;

  protected $fillable = ['instance_id','swarm_hash','status', 'created_at'];
}
