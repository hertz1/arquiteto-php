<?php

namespace Database\Factories;

use App\Models\Address;
use App\Models\Location;
use App\Models\LocationType;
use Illuminate\Database\Eloquent\Factories\Factory;

class AddressFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Address::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        /** @var Location $city */
        $city = Location::cities()->inRandomOrder()->first();
        $attributes = [
            'location_type_id' => LocationType::DISTRICT,
            'name' => $this->faker->streetName,
            'children' => [[
                'location_type_id' => LocationType::POSTAL_CODE,
                'name' => $this->faker->postcode,
            ]]
        ];
        $district = Location::create($attributes, $city);
        $postalCode = $district->descendants->first();

        return [
            'address_line_1' => $this->faker->streetAddress,
            'address_line_2' => $this->faker->secondaryAddress,
            'location_id'    => $postalCode->id
        ];
    }
}
