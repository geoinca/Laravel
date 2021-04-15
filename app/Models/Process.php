<?php
//use App\Models\User;
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Process extends Model
{
    use HasFactory;
        //
        public function user()
        {
            return $this->belongsTo(User::class);
        }

        public function wasCreatedBy($user)
        {
            if( is_null($user) ) {
                return false;
            }

            return $this->user_id === $user->id;
        }

        protected $fillable = [
            'user_id', 'url'
        ]; 
        
}
