<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class HolidayPlan extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * @var array<string> $fillable
     */
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'date',
        'location',
        'participants',
    ];

    /***
     * @var array<string, string> $casts
     */
    protected $casts = [
        'date' => 'date:Y-m-d',
        'participants' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getDateAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d');
    }

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d H:i:s');
    }
}
