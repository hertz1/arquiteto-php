<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Kalnoy\Nestedset\Collection;
use Rennokki\QueryCache\Traits\QueryCacheable;

/**
 * App\Models\Address
 *
 * @property int $id
 * @property string $user_uuid
 * @property string $address_line_1
 * @property string $address_line_2
 * @property int $location_id
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read Location $location
 * @method static Builder|Address newModelQuery()
 * @method static Builder|Address newQuery()
 * @method static Builder|Address query()
 * @method static Builder|Address whereAddressLine1($value)
 * @method static Builder|Address whereAddressLine2($value)
 * @method static Builder|Address whereCreatedAt($value)
 * @method static Builder|Address whereId($value)
 * @method static Builder|Address whereLocationId($value)
 * @method static Builder|Address whereUpdatedAt($value)
 * @method static Builder|Address whereUserUuid($value)
 * @mixin \Eloquent
 */
class Address extends Model
{
    use HasFactory, QueryCacheable;

    public $guarded = ['location'];

    public $cacheFor = Carbon::HOURS_PER_DAY *
                       Carbon::MINUTES_PER_HOUR *
                       Carbon::SECONDS_PER_MINUTE;

    protected static bool $flushCacheOnUpdate = true;

    /**
     * The location relationship
     * @return BelongsTo
     */
    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    /**
     * Get a flat tree representation of the address' locations
     * @return Collection
     */
    public function getLocationsFlatTree(): Collection
    {
        $locations = collect([$this->location])
            ->merge($this->location->ancestors->toFlatTree()->reverse());

        return new Collection($locations);
    }

    /**
     * Filter by the user uuid
     * @param Builder $query
     * @param $uuid
     */
    public function scopeWhereUserUuid(Builder $query, $uuid)
    {
        $query->where('user_uuid', $uuid);
    }

    /**
     * Get the associated country of the address
     * @return Location
     */
    public function getCountry(): Location
    {
        return $this->location
                    ->countries()
                    ->first();
    }
}
