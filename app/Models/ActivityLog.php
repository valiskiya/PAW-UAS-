<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'level',
        'action',
        'description',
        'ip_address',
        'user_agent',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Helper statis untuk mencatat log.
     *
     * @param  string          $action
     * @param  string|null     $description
     * @param  string          $level
     * @param  \App\Models\User|null $user
     */
    public static function record(
        string $action,
        ?string $description = null,
        string $level = 'info',
        ?User $user = null
    ): void {
        try {
            $req = request();
            $userId = $user?->id ?? (auth()->check() ? auth()->id() : null);

            self::create([
                'user_id'    => $userId,
                'level'      => $level,
                'action'     => $action,
                'description'=> $description,
                'ip_address' => $req?->ip(),
                'user_agent' => $req?->userAgent(),
            ]);
        } catch (\Throwable $e) {
            // Jangan sampai error log bikin aplikasi crash
        }
    }
}
