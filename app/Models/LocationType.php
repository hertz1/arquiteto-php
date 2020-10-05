<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Rennokki\QueryCache\Traits\QueryCacheable;

/**
 * App\Models\LocationType
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|LocationType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LocationType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LocationType query()
 * @method static \Illuminate\Database\Eloquent\Builder|LocationType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LocationType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LocationType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LocationType whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class LocationType extends Model
{
    use HasFactory, QueryCacheable;

    public const COUNTRY     = 1;
    public const STATE       = 2;
    public const CITY        = 3;
    public const DISTRICT    = 4;
    public const POSTAL_CODE = 5;

    public int $cacheFor = Carbon::HOURS_PER_DAY *
                           Carbon::MINUTES_PER_HOUR *
                           Carbon::SECONDS_PER_MINUTE;
}
