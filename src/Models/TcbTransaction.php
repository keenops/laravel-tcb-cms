<?php

declare(strict_types=1);

namespace Keenops\LaravelTcbCms\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class TcbTransaction extends Model
{
    protected $fillable = [
        'type',
        'reference',
        'status',
        'request',
        'response',
        'error_message',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'request' => 'array',
            'response' => 'array',
        ];
    }

    public function getTable(): string
    {
        return config('tcb-cms.logging.table', 'tcb_cms_transactions');
    }

    /**
     * Scope to filter by type.
     *
     * @param  Builder<self>  $query
     * @return Builder<self>
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope to filter by reference.
     *
     * @param  Builder<self>  $query
     * @return Builder<self>
     */
    public function scopeForReference($query, string $reference)
    {
        return $query->where('reference', $reference);
    }

    /**
     * Scope to filter successful transactions.
     *
     * @param  Builder<self>  $query
     * @return Builder<self>
     */
    public function scopeSuccessful($query)
    {
        return $query->where('status', 'success');
    }

    /**
     * Scope to filter failed transactions.
     *
     * @param  Builder<self>  $query
     * @return Builder<self>
     */
    public function scopeFailed($query)
    {
        return $query->where('status', 'failure');
    }
}
