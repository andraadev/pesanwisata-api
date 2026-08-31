<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;


#[Fillable(['booking_date', 'user_id', 'destination_id', 'status'])]

class Booking extends Model
{
    /** @use HasFactory<BookingFactory> */
    use HasFactory, Notifiable, HasApiTokens;
}
