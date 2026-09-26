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

class Destination extends Model
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;
    protected $appends = ['image_url'];

    protected function imageUrl(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                if (!$value) return null;

                if (filter_var($value, FILTER_VALIDATE_URL)) {
                    return $value;
                }

                return asset('storage/' . $value);
            }
        );
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}
