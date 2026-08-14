<?php namespace Seiger\sMailer\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Persisted campaign document owned by the sMailer Builder.
 *
 * The JSON document is the source of truth. HTML rendering, delivery runs,
 * revisions, and audience rules intentionally remain separate increments.
 */
class Mailing extends Model
{
    protected $table = 's_mailings';

    protected $fillable = [
        'name',
        'domain',
        'lang',
        'status',
        'delivery_mode',
        'scheduled_at',
        'document',
    ];

    protected function casts(): array
    {
        return [
            'document' => 'array',
            'scheduled_at' => 'datetime',
        ];
    }
}
