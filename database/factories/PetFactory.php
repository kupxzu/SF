<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pet>
 */
class PetFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $petType = fake()->randomElement(['canine', 'feline']);
        
        $canineBreeds = ['Labrador', 'German Shepherd', 'Golden Retriever', 'Bulldog', 'Beagle', 'Poodle', 'Chihuahua'];
        $felineBreeds = ['Persian', 'Siamese', 'Maine Coon', 'Bengal', 'British Shorthair', 'Ragdoll', 'Sphynx'];
        
        return [
            'client_id' => \App\Models\Client::factory(),
            'petname' => fake()->firstName(),
            'pet_type' => $petType,
            'breed' => $petType === 'canine' ? fake()->randomElement($canineBreeds) : fake()->randomElement($felineBreeds),
            'colormarkings' => fake()->randomElement(['Brown', 'Black', 'White', 'Golden', 'Gray', 'Mixed', 'Tabby', 'Spotted']),
        ];
    }
}
