<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id', 'action', 'description',
        'loggable_type', 'loggable_id', 'ip_address',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function log(string $action, string $description, mixed $loggable = null): static
    {
        return static::create([
            'user_id'       => auth()->id(),
            'action'        => $action,
            'description'   => $description,
            'loggable_type' => $loggable ? get_class($loggable) : null,
            'loggable_id'   => $loggable?->id,
            'ip_address'    => request()->ip(),
        ]);
    }
}
