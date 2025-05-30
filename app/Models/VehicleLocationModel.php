<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VehicleLocationModel extends Model {
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = "vehicle_locations";

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        "vehicle_id",
        "latitude",
        "longitude",
        "speed",
        "heading",
        "timestamp",
        "created_at",
        "updated_at",
        "deleted_at"
    ];

    public function vehicle() {
        return $this->belongsTo(VehicleModel::class, "vehicle_id");
    }
}
