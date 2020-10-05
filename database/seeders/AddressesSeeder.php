<?php

namespace Database\Seeders;

use App\Models\Address;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AddressesSeeder extends Seeder
{
    protected int $usersAmount = 50;
    protected int $addressedPerUser = 3;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Address::factory()
            ->count(($this->usersAmount * $this->addressedPerUser) + 1)
            ->state($this->getUsersUuidsSequence())
            ->create();
    }

    protected function getUsersUuidsSequence(): Sequence
    {
        $uuids = ['3c472ed8-87c8-4fee-a51a-c0401e9507f8'];

        for ($i = 0; $i < $this->usersAmount; $i++)
            $uuids[] = (string) Str::uuid();

        return new Sequence(...array_map(
            fn ($uuid) => ['user_uuid' => (string) $uuid],
            $uuids
        ));
    }
}
