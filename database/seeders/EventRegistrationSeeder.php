<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Event;
use App\Models\User;
use App\Models\EventRegistration;
use Illuminate\Support\Str;

class EventRegistrationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get some events and users
        $events = Event::all();
        $users = User::where('role', 'customer')->get();

        if ($events->isEmpty() || $users->isEmpty()) {
            $this->command->info('Please seed events and users first!');
            return;
        }

        $experienceLevels = ['beginner', 'intermediate', 'advanced'];
        $registrationStatuses = ['pending', 'confirmed', 'cancelled'];
        $paymentStatuses = ['pending', 'paid', 'failed', 'free'];
        $paymentMethods = ['transfer', 'credit_card', 'e-wallet'];

        $existingRegistrations = collect();
        $registrationsCreated = 0;
        $maxAttempts = 20; // Batas maksimal percobaan untuk menghindari infinite loop
        $attempts = 0;

        // Create 10 registrations
        while ($registrationsCreated < 10 && $attempts < $maxAttempts) {
            $user = $users->random();
            $event = $events->random();
            
            // Skip if user already registered for this event
            if ($existingRegistrations->contains(function ($item) use ($event, $user) {
                return $item['event_id'] === $event->id && $item['email'] === $user->email;
            })) {
                $attempts++;
                continue;
            }

            // Generate unique confirmation code
            $confirmationCode = 'REG-' . strtoupper(Str::random(8));
            
            // Set payment status based on event
            $paymentStatus = $event->is_free ? 'free' : $paymentStatuses[array_rand(array_filter($paymentStatuses, fn($status) => $status !== 'free'))];
            
            $registration = [
                'event_id' => $event->id,
                'user_id' => $user->id,
                'confirmation_code' => $confirmationCode,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone ?? fake()->phoneNumber(),
                'company' => fake()->company(),
                'position' => fake()->jobTitle(),
                'experience_level' => $experienceLevels[array_rand($experienceLevels)],
                'motivation' => fake()->paragraph(),
                'dietary_requirements' => rand(0, 1) ? fake()->words(3, true) : null,
                'special_needs' => rand(0, 1) ? fake()->sentence() : null,
                'registration_status' => $registrationStatuses[array_rand($registrationStatuses)],
                'payment_status' => $paymentStatus,
                'payment_method' => $event->is_free ? null : $paymentMethods[array_rand($paymentMethods)],
                'payment_amount' => $event->is_free ? 0 : $event->price,
                'registration_date' => now()->subDays(rand(1, 30)),
                'created_at' => now(),
                'updated_at' => now(),
            ];

            try {
                EventRegistration::create($registration);
                $existingRegistrations->push($registration);
                $registrationsCreated++;
                $this->command->info("Created registration {$registrationsCreated}/10");
            } catch (\Exception $e) {
                $this->command->error("Error creating registration: " . $e->getMessage());
                $attempts++;
            }
        }

        if ($registrationsCreated === 10) {
            $this->command->info('Created 10 event registrations successfully!');
        } else {
            $this->command->warn("Created only {$registrationsCreated} registrations due to unique constraint limitations.");
        }
    }
}
