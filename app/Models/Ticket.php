<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $fillable = [
        'ticket_number',
        'title',
        'description',
        'category_id',
        'priority',
        'status',
        'user_id',
        'assigned_to',
        'department_id',
        'site_type',
        'source_name',
        'extension_number',
        'resolution_notes',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class);
    }

    public function getSiteTypeLabelAttribute()
    {
        return match ($this->site_type) {
            'hq' => 'HQ',
            'branch' => 'Branch',
            default => null,
        };
    }

    public function getSourceDisplayAttribute()
    {
        if ($this->source_name && $this->extension_number) {
            return "{$this->source_name} ({$this->extension_number})";
        }

        return $this->source_name ?? $this->extension_number ?? null;
    }

    protected $casts = [
    'created_at' => 'datetime',
    'updated_at' => 'datetime',
];

}