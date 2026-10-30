<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

$endpoint = '/api/admin/users';

describe('index', function () use ($endpoint) {
    it('allows admin to retrieve a paginated list of users', function () use ($endpoint) {
        $admin = User::factory()->create(['role' => 'Admin']);
        User::factory()->count(20)->create(['role' => 'User']);

        $this->actingAs($admin, 'sanctum');

        $response = $this->getJson($endpoint);

        $response->assertOk()
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'current_page',
                    'data' => [
                        '*' => ['id', 'name', 'email', 'role'],
                    ],
                    'per_page',
                    'total',
                ],
            ])
            ->assertJsonFragment(['success' => true])
            ->assertJsonPath('data.per_page', 15)
            ->assertJsonPath('data.total', 21);
    });

    it('forbids non-admin users from listing users', function () use ($endpoint) {
        $user = User::factory()->create(['role' => 'User']);

        $this->actingAs($user, 'sanctum');

        $this->getJson($endpoint)->assertForbidden();
    });

    it('rejects unauthenticated requests to list users', function () use ($endpoint) {
        $this->getJson($endpoint)->assertUnauthorized();
    });
});

describe('store', function () use ($endpoint) {
    it('allows admin to create a user with valid data', function () use ($endpoint) {
        $admin = User::factory()->create(['role' => 'Admin']);

        $this->actingAs($admin, 'sanctum');

        $payload = [
            'name' => 'Budi Santoso',
            'email' => 'budi@example.com',
            'password' => 'secret123',
            'role' => 'User',
        ];

        $response = $this->postJson($endpoint, $payload);

        $response->assertCreated()
            ->assertJsonStructure(['success', 'message', 'data' => ['id', 'name', 'email', 'role']])
            ->assertJsonFragment(['email' => 'budi@example.com'])
            ->assertJsonMissing(['password' => 'secret123']);

        $this->assertDatabaseHas('users', [
            'email' => 'budi@example.com',
            'role' => 'User',
        ]);
    });

    it('stores the password hashed, not as plain text', function () use ($endpoint) {
        $admin = User::factory()->create(['role' => 'Admin']);

        $this->actingAs($admin, 'sanctum');

        $this->postJson($endpoint, [
            'name' => 'Hashed User',
            'email' => 'hashed@example.com',
            'password' => 'secret123',
            'role' => 'User',
        ])->assertCreated();

        $user = User::where('email', 'hashed@example.com')->firstOrFail();

        expect($user->password)->not->toBe('secret123')
            ->and(Hash::check('secret123', $user->password))->toBeTrue();
    });

    it('fails validation when required fields are missing', function () use ($endpoint) {
        $admin = User::factory()->create(['role' => 'Admin']);

        $this->actingAs($admin, 'sanctum');

        $this->postJson($endpoint, [])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'email', 'password', 'role']);
    });

    it('fails validation when email is duplicate, invalid, or role is invalid', function () use ($endpoint) {
        $admin = User::factory()->create(['role' => 'Admin']);
        User::factory()->create(['email' => 'taken@example.com', 'role' => 'User']);

        $this->actingAs($admin, 'sanctum');

        $this->postJson($endpoint, [
            'name' => 'Dup',
            'email' => 'taken@example.com',
            'password' => 'secret123',
            'role' => 'User',
        ])->assertStatus(422)->assertJsonValidationErrors(['email']);

        $this->postJson($endpoint, [
            'name' => 'Bad',
            'email' => 'not-an-email',
            'password' => 'short',
            'role' => 'SuperAdmin',
        ])->assertStatus(422)->assertJsonValidationErrors(['email', 'password', 'role']);
    });

    it('forbids non-admin from creating users', function () use ($endpoint) {
        $this->actingAs(User::factory()->create(['role' => 'User']), 'sanctum');

        $this->postJson($endpoint, [
            'name' => 'Nope',
            'email' => 'nope@example.com',
            'password' => 'secret123',
            'role' => 'User',
        ])->assertForbidden();
    });

    it('rejects unauthenticated create requests', function () use ($endpoint) {
        $this->postJson($endpoint, [
            'name' => 'Nope',
            'email' => 'nope@example.com',
            'password' => 'secret123',
            'role' => 'User',
        ])->assertUnauthorized();
    });
});

describe('show', function () use ($endpoint) {
    it('allows admin to view any user profile', function () use ($endpoint) {
        $admin = User::factory()->create(['role' => 'Admin']);
        $target = User::factory()->create(['role' => 'User']);

        $this->actingAs($admin, 'sanctum');

        $this->getJson("{$endpoint}/{$target->id}")
            ->assertOk()
            ->assertJsonStructure(['success', 'message', 'data' => ['id', 'name', 'email', 'role']])
            ->assertJsonFragment(['email' => $target->email]);
    });

    it('forbids regular users from viewing profiles, including their own', function () use ($endpoint) {
        $user = User::factory()->create(['role' => 'User']);
        $other = User::factory()->create(['role' => 'User']);

        $this->actingAs($user, 'sanctum');

        $this->getJson("{$endpoint}/{$user->id}")->assertForbidden();
        $this->getJson("{$endpoint}/{$other->id}")->assertForbidden();
    });

    it('rejects unauthenticated requests to view a user', function () use ($endpoint) {
        $target = User::factory()->create(['role' => 'User']);

        $this->getJson("{$endpoint}/{$target->id}")->assertUnauthorized();
    });

    it('returns a custom 404 payload for a missing user', function () use ($endpoint) {
        $this->actingAs(User::factory()->create(['role' => 'Admin']), 'sanctum');

        $this->getJson("{$endpoint}/999999")
            ->assertNotFound()
            ->assertJsonFragment(['success' => false]);
    });
});

describe('update', function () use ($endpoint) {
    it('allows admin to update any user details', function () use ($endpoint) {
        $admin = User::factory()->create(['role' => 'Admin']);
        $target = User::factory()->create(['role' => 'User', 'name' => 'Old Name']);

        $this->actingAs($admin, 'sanctum');

        $this->putJson("{$endpoint}/{$target->id}", [
            'name' => 'New Name',
            'email' => $target->email,
            'role' => 'User',
        ])
            ->assertOk()
            ->assertJsonFragment(['name' => 'New Name']);

        expect($target->fresh()->name)->toBe('New Name');
    });

    it('ignores the unique email rule for the user being updated', function () use ($endpoint) {
        $admin = User::factory()->create(['role' => 'Admin']);
        $target = User::factory()->create(['email' => 'keep@example.com', 'role' => 'User']);

        $this->actingAs($admin, 'sanctum');

        $this->putJson("{$endpoint}/{$target->id}", [
            'name' => 'New Name',
            'email' => 'keep@example.com',
            'role' => 'User',
        ])->assertOk();
    });

    it('allows updating user details without changing the password', function () use ($endpoint) {
        $admin = User::factory()->create(['role' => 'Admin']);
        $oldHash = Hash::make('oldpassword');
        $target = User::factory()->create(['password' => $oldHash, 'role' => 'User']);

        $this->actingAs($admin, 'sanctum');

        $this->putJson("{$endpoint}/{$target->id}", [
            'name' => 'Updated Name',
            'email' => $target->email,
            'role' => 'User',
        ])->assertOk();

        expect($target->fresh()->password)->toBe($oldHash);
    });

    it('fails validation when updating email to an existing email of another user', function () use ($endpoint) {
        $admin = User::factory()->create(['role' => 'Admin']);
        User::factory()->create(['email' => 'other@example.com']);
        $target = User::factory()->create(['email' => 'target@example.com']);

        $this->actingAs($admin, 'sanctum');

        $this->putJson("{$endpoint}/{$target->id}", [
            'name' => 'Target Name',
            'email' => 'other@example.com',
            'role' => 'User',
        ])->assertStatus(422)->assertJsonValidationErrors(['email']);
    });

    it('forbids non-admin from updating users', function () use ($endpoint) {
        $user = User::factory()->create(['role' => 'User']);
        $target = User::factory()->create();

        $this->actingAs($user, 'sanctum');

        $this->putJson("{$endpoint}/{$target->id}", [
            'name' => 'Hacked Name',
            'email' => $target->email,
            'role' => 'User',
        ])->assertForbidden();
    });
});

describe('destroy', function () use ($endpoint) {
    it('allows admin to delete another user', function () use ($endpoint) {
        $admin = User::factory()->create(['role' => 'Admin']);
        $target = User::factory()->create(['role' => 'User']);

        $this->actingAs($admin, 'sanctum');

        $this->deleteJson("{$endpoint}/{$target->id}")
            ->assertOk()
            ->assertJsonFragment(['success' => true]);

        $this->assertDatabaseMissing('users', ['id' => $target->id]);
    });

    it('prevents admin from deleting their own account (safeguard)', function () use ($endpoint) {
        $admin = User::factory()->create(['role' => 'Admin']);

        $this->actingAs($admin, 'sanctum');

        $this->deleteJson("{$endpoint}/{$admin->id}")->assertForbidden();

        $this->assertDatabaseHas('users', ['id' => $admin->id]);
    });

    it('forbids non-admin users from deleting accounts', function () use ($endpoint) {
        $user = User::factory()->create(['role' => 'User']);
        $target = User::factory()->create();

        $this->actingAs($user, 'sanctum');

        $this->deleteJson("{$endpoint}/{$target->id}")->assertForbidden();
    });

    it('rejects unauthenticated delete requests', function () use ($endpoint) {
        $target = User::factory()->create();

        $this->deleteJson("{$endpoint}/{$target->id}")->assertUnauthorized();
    });
});
