<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Day extends Model
{
    protected $fillable = ['date','study_seconds','examples_solved','sessions','user_id'];
    protected $casts = ['sessions' => 'array', 'date' => 'date'];
    public function user(){ return $this->belongsTo(User::class); }
}
