<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Queue extends Model
{
    use HasFactory;

    protected $fillable = ['job_id', 'job_pickup_name', 'job_pickup_address' ,'job_pickup_datetime', 'geofence_details'];
}
