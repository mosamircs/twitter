<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tweet extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'type',
        'body',
        'user_id',
        'base_id',
        'parent_id'
    ];


    // /**
    //  * relation that can make comments inside tweet
    //  *
    //  * @return \Illuminate\Database\Eloquent\Relations\HasMany
    //  */
    // public function comments()
    // {
    //     return $this->hasMany(__CLASS__, 'base_id');
    // }
    // /**
    //  * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
    //  */
    // public function comment()
    // {
    //     return $this->belongsTo(__CLASS__, 'base_id');
    // }

    // /**
    //  * relation that can make reply inside replies
    //  *
    //  * @return \Illuminate\Database\Eloquent\Relations\HasMany
    //  */
    // public function replies()
    // {
    //     return $this->hasMany(__CLASS__, 'parent_id');
    // }
    // /**
    //  * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
    //  */
    // public function reply()
    // {
    //     return $this->belongsTo(__CLASS__, 'parent_id');
    // }

    public function user(){
        return $this->belongsTo(User::class);
    }
    public function commentChild(){
        return $this->hasMany(__CLASS__, 'base_id');
    }
    public function commentParent(){
        return $this->belongsTo(__CLASS__, 'base_id');
    }
    public function replyChild(){
        return $this->hasMany(__CLASS__, 'parent_id');
    }
    public function replyParent(){
        return $this->belongsTo(__CLASS__, 'parent_id');
    }
}
