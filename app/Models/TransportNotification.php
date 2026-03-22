<?php

namespace App\Models;

use App\Mail\TransportNotificationMail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Mail;

class TransportNotification extends Model
{
    use HasFactory;

    protected $table = 'transport_notifications';

    protected $fillable = [
        'user_id',
        'trip_id',
        'type',
        'title',
        'message',
        'meta',
        'is_read',
        'read_at',
        'push_sent',
        'push_sent_at',
    ];

    protected function casts(): array
    {
        return [
            'meta'         => 'array',
            'is_read'      => 'boolean',
            'push_sent'    => 'boolean',
            'read_at'      => 'datetime',
            'push_sent_at' => 'datetime',
        ];
    }

    // ── Scopes ───────────────────────────────────────────────────

    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function scopeForUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }

    public function scopeRecent($query, int $days = 7)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    // ── Relationships ────────────────────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function trip(): BelongsTo
    {
        return $this->belongsTo(Trip::class);
    }

    // ── State helpers ────────────────────────────────────────────

    public function markAsRead(): void
    {
        if (! $this->is_read) {
            $this->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
        }
    }

    // ── Factory / builder ────────────────────────────────────────

    /**
     * Quick factory to send a notification to a user.
     *
     * Usage:
     *   TransportNotification::send(
     *       user:    $parent,
     *       type:    'bus_approaching',
     *       title:   'Bus approaching!',
     *       message: 'Your child\'s bus is ~5 minutes away.',
     *       trip:    $trip,
     *       meta:    ['eta_minutes' => 5, 'stop_name' => 'Kicukiro Centre'],
     *   );
     */
    public static function send(
        User   $user,
        string $type,
        string $title,
        string $message,
        ?Trip  $trip  = null,
        array  $meta  = [],
        bool   $email = true,
    ): self {
        $notification = self::create([
            'user_id' => $user->id,
            'trip_id' => $trip?->id,
            'type'    => $type,
            'title'   => $title,
            'message' => $message,
            'meta'    => $meta,
        ]);

        // Send email if the user has an email address and email is not suppressed
        if ($email && $user->email) {
            Mail::to($user->email, $user->name)
                ->queue(new TransportNotificationMail($notification->load('user', 'trip.route', 'trip.driver', 'trip.vehicle')));
        }

        return $notification;
    }

    // ── Accessors ────────────────────────────────────────────────

    public function getIconAttribute(): string
    {
        return match ($this->type) {
            'trip_started'   => 'bi-play-circle-fill',
            'trip_completed' => 'bi-check-circle-fill',
            'bus_approaching'=> 'bi-geo-alt-fill',
            'bus_arrived'    => 'bi-flag-fill',
            'trip_delayed'   => 'bi-clock-fill',
            'trip_cancelled' => 'bi-x-circle-fill',
            'sos'            => 'bi-exclamation-triangle-fill',
            default          => 'bi-bell-fill',
        };
    }

    public function getColorAttribute(): string
    {
        return match ($this->type) {
            'trip_started',
            'trip_completed',
            'bus_arrived'    => 'success',
            'bus_approaching'=> 'info',
            'trip_delayed'   => 'warning',
            'trip_cancelled',
            'sos'            => 'danger',
            default          => 'secondary',
        };
    }

    public function getTimeAgoAttribute(): string
    {
        return $this->created_at->diffForHumans();
    }
}