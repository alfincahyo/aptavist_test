<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Matches extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $table = 'matches';

    public function home_club()
    {
        return $this->belongsTo(Club::class, 'home_club_id');
    }

    public function away_club()
    {
        return $this->belongsTo(Club::class, 'away_club_id');
    }
}
