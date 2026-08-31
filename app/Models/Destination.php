<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Casts\Attribute;

#[Fillable(['name', 'slug', 'location', 'description', 'image_url'])]
#[Hidden(['password', 'remember_token'])]

class Destination extends Model
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    protected function image(): Attribute
    {
        return Attribute::make(
            get: fn($image) => url('/storage/destinations_image/' . $image),
        );
    }
}
