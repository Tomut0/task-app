<?php

namespace App\Models;

use App\DateUtil;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 *
 *
 * @property int $id
 * @property string $title
 * @property string $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $users
 * @property-read int|null $users_count
 * @method static Builder|Task newModelQuery()
 * @method static Builder|Task newQuery()
 * @method static Builder|Task query()
 * @method static Builder|Task whereCreatedAt($value)
 * @method static Builder|Task whereDescription($value)
 * @method static Builder|Task whereId($value)
 * @method static Builder|Task whereTitle($value)
 * @method static Builder|Task whereUpdatedAt($value)
 * @method static Builder|Task whereDateBetween($fieldName, $fromDate, $todate)
 * @mixin \Eloquent
 */
class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'created_at',
        'expired_at'
    ];

    protected $casts = [
        'created_at' => 'timestamp',
        'expired_at' => 'timestamp'
    ];

    public function formattedDate(): string
    {
        return $this->created_at->format('d-m-Y');
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    /**
     * Scope a query to only include the last n days records
     *
     * @param Builder $query
     * @param $fieldName
     * @param $fromDate
     * @param $toDate
     * @return Builder
     */
    public function scopeWhereDateBetween(Builder $query, string $fieldName, Carbon|string $fromDate, Carbon|string $toDate): Builder
    {
        return $query->where($fieldName,'>=', $fromDate)
            ->where($fieldName,'<=', $toDate);
    }
}
