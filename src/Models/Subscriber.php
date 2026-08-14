<?php namespace Seiger\sMailer\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * A recipient owned by the current sMailer package schema.
 *
 * The table deliberately stores only the contact identity and subscription
 * lifecycle. Campaign membership and delivery results remain separate future
 * concerns.
 */
class Subscriber extends Model
{
    protected $table = 's_subscribers';

    protected $fillable = [
        'domain',
        'lang',
        'email',
        'name',
        'status',
        'subscribed_at',
        'unsubscribed_at',
        'blocked_at',
    ];

    protected function casts(): array
    {
        return [
            'subscribed_at' => 'datetime',
            'unsubscribed_at' => 'datetime',
            'blocked_at' => 'datetime',
        ];
    }
}
