<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvitedSpeaker extends Model
{
    use HasFactory;

    protected $table = 'invited_speakers';

    public function speaker () {

        return $this->belongsTo(Speaker::class,'speaker_id','id');
    }

}
