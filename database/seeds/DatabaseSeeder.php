<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Organization;
use App\Countrie;
use App\Department;
use App\Citie;
use App\Propertie;
use App\Owner;
use App\PropertiesType;

class DatabaseSeeder extends Seeder
{
    public function run()
    {

        factory(User::class)->create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'johndoe@example.com',
            'owner' => true,
        ]);

        factory(User::class, 5)->create();

        $countrie = factory(Countrie::class)->create();
        $department = factory(Department::class)->create(['countrie_id' => $countrie->id]);
        $citie = factory(Citie::class)->create(['department_id' => $department->id]);

        $organization = factory(Organization::class)
            ->create(['citie_id' => $citie->id]);

        $owner = factory(Owner::class)->create();

        $properties_type = factory(PropertiesType::class)->create([
            'name' => 'Alquiler',
        ]);

        $properties = factory(Propertie::class, 3)
            ->create([
                'citie_id' => $citie->id,
                'owner_id' => $owner->id,
                'organization_id' => $organization->id,
                'properties_type_id' => $properties_type->id,
                ]);
    }
}
