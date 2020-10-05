<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NodeTrait;
use Rennokki\QueryCache\Traits\QueryCacheable;

/**
 * App\Models\Location
 *
 * @property int $id
 * @property string $name
 * @property int $location_type_id
 * @property int $_lft
 * @property int $_rgt
 * @property int|null $parent_id
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \Kalnoy\Nestedset\Collection|Location[] $children
 * @property-read int|null $children_count
 * @property-read Location|null $parent
 * @property-read LocationType $type
 * @method static \Kalnoy\Nestedset\Collection|static[] all($columns = ['*'])
 * @method static Builder|Location cities()
 * @method static Builder|Location countries()
 * @method static Builder|Location countryId(int $countryId)
 * @method static Builder|Location d()
 * @method static \Kalnoy\Nestedset\Collection|static[] get($columns = ['*'])
 * @method static \Kalnoy\Nestedset\QueryBuilder|Location newModelQuery()
 * @method static \Kalnoy\Nestedset\QueryBuilder|Location newQuery()
 * @method static \Kalnoy\Nestedset\QueryBuilder|Location query()
 * @method static Builder|Location whereCreatedAt($value)
 * @method static Builder|Location whereId($value)
 * @method static Builder|Location whereLft($value)
 * @method static Builder|Location whereLocationTypeId($value)
 * @method static Builder|Location whereName($value)
 * @method static Builder|Location whereParentId($value)
 * @method static Builder|Location whereRgt($value)
 * @method static Builder|Location whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Location extends Model
{
    use HasFactory, NodeTrait, QueryCacheable;

    public $fillable = ['id', 'name', 'location_type_id'];

    public $cacheFor = Carbon::HOURS_PER_DAY *
                       Carbon::MINUTES_PER_HOUR *
                       Carbon::SECONDS_PER_MINUTE;

    protected static bool $flushCacheOnUpdate = true;

    public function type()
    {
        return $this->belongsTo(LocationType::class, 'location_type_id');
    }

    public function parent()
    {
        return $this->belongsTo(self::class);
    }

    public function scopeCountries(Builder $query)
    {
        $query->where('location_type_id', LocationType::COUNTRY);
    }

    public function scopeCities(Builder $query)
    {
        $query->where('location_type_id', LocationType::CITY);
    }

    /**
     * @param Builder|Location $query
     * @param int $countryId
     */
    public function scopeCountryId(Builder $query, int $countryId)
    {
        $query->countries()
              ->where('id', $countryId);
    }

    /**
     * Retorna a ordem da árvore de localidades por país
     *
     * @param string $countryName
     * @return array
     */
    public static function getLocationTypeOrderByCountry(string $countryName): array
    {
        switch (strtoupper($countryName)) {
            case 'BRAZIL':
                // No Brasil a cidade já está vinculada a uma árvore,
                // precisamos apenas vincular o distrito (bairro) e CEP
                return [LocationType::CITY, LocationType::DISTRICT, LocationType::POSTAL_CODE];

            default:
                return [LocationType::COUNTRY, LocationType::STATE, LocationType::CITY, LocationType::DISTRICT, LocationType::POSTAL_CODE];
        }
    }

    /**
     * Retorna o tipo de localidade raíz que será excluído, por nome de país
     *
     * @param string $countryName
     * @return int
     */
    public static function getRootLocationTypeForDeletionByCountry(string $countryName): int
    {
        switch (strtoupper($countryName)) {
            case 'BRAZIL':
                // No Brasil, removes o nó do distrito (bairro) e filhos
                return LocationType::DISTRICT;

            default:
                return LocationType::STATE;
        }
    }
}
