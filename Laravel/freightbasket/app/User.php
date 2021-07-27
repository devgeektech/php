<?php

namespace App;
  
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use \TCG\Voyager\Models\User as Authenticatable;
use Laravel\Cashier\Billable;

class User extends Authenticatable
{
    use Notifiable;
    use Billable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'phone', 'role_id','about','country','address','background_image',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    
    public function verifyUser()
    {
      return $this->hasOne('App\VerifyUser');
    }

    public function posts(){
        return $this->hasMany('App\Timeline', 'user_id', 'id');
    }

    public function freights(){
        return $this->hasMany('App\Freight', 'user_id', 'id')->where('status', '1');
    }

    public function other_services(){
        return $this->hasMany('App\Otherservice', 'user_id', 'id');
    }
    public function notification(){
        return $this->hasMany('App\Notification', 'sender_id', 'id')->where('status', '1');
    }

    public function getPhoto($w = null, $h = null){
        if (!empty($this->avatar)){
            $path = 'uploads/profiles/'.$this->avatar;
        }else {
            $path = "uploads/profiles/default-user-photo.jpg";
        }
        return url('/'.$path);
        if ($w == null && $h == null){
        }
        $image = '/?';
        if ($w > -1) $image .= '&w='.$w;
        if ($h > -1) $image .= '&h='.$h;
        $image .= '&zc=1';
        $image .= '&src='.$path;
        return url($image);
    }


    //======================== functions to get friends attribute =========================

    // friendship that this user started
    protected function friendsOfThisUser()
    {
        return $this->belongsToMany(User::class, 'friendships', 'first_user', 'second_user')
        ->withPivot('status')
        ->wherePivot('status', 'confirmed');
    }

    // friendship that this user was asked for
    protected function thisUserFriendOf()
    {
        return $this->belongsToMany(User::class, 'friendships', 'second_user', 'first_user')
        ->withPivot('status')
        ->wherePivot('status', 'confirmed');
    }

    // accessor allowing you call $user->friends
    public function getFriendsAttribute()
    {
        if ( ! array_key_exists('friends', $this->relations)) $this->loadFriends();
        return $this->getRelation('friends');
    }

    protected function loadFriends()
    {
        if ( ! array_key_exists('friends', $this->relations))
        {
        $friends = $this->mergeFriends();
        $this->setRelation('friends', $friends);
    }
    }

    protected function mergeFriends()
    {
        if($temp = $this->friendsOfThisUser)
        return $temp->merge($this->thisUserFriendOf);
        else
        return $this->thisUserFriendOf;
    }
    //======================== end functions to get friends attribute =========================

    //====================== functions to get blocked_friends attribute ============================

    // friendship that this user started but now blocked
    protected function friendsOfThisUserBlocked()
    {
        return $this->belongsToMany(User::class, 'friendships', 'first_user', 'second_user')
                    ->withPivot('status', 'acted_user')
                    ->wherePivot('status', 'blocked')
                    ->wherePivot('acted_user', 'first_user');
    }

    // friendship that this user was asked for but now blocked
    protected function thisUserFriendOfBlocked()
    {
        return $this->belongsToMany(User::class, 'friendships', 'second_user', 'first_user')
                    ->withPivot('status', 'acted_user')
                    ->wherePivot('status', 'blocked')
                    ->wherePivot('acted_user', 'second_user');
    }

    // accessor allowing you call $user->blocked_friends
    public function getBlockedFriendsAttribute()
    {
        if ( ! array_key_exists('blocked_friends', $this->relations)) $this->loadBlockedFriends();
            return $this->getRelation('blocked_friends');
    }

    protected function loadBlockedFriends()
    {
        if ( ! array_key_exists('blocked_friends', $this->relations))
        {
            $friends = $this->mergeBlockedFriends();
            $this->setRelation('blocked_friends', $friends);
        }
    }

    protected function mergeBlockedFriends()
    {
        if($temp = $this->friendsOfThisUserBlocked)
            return $temp->merge($this->thisUserFriendOfBlocked);
        else
            return $this->thisUserFriendOfBlocked;
    }
    // ======================================= end functions to get block_friends attribute =========

    public function friend_requests()
    {
        return $this->hasMany(Friendship::class, 'second_user')
        ->where('status', 'pending');
    }
    public function friend_requests_first_user()
    {
        return $this->hasMany(Friendship::class, 'first_user');
    }


    public function canSeeProfile($user_id){
        if ($this->id == $user_id || !$this->isPrivate()) return true;
        $relation = $this->follower()->where('follower_user_id', $user_id)->where('allow', 1)->get()->first();
        if ($relation){
            return true;
        }
        return false;
    }

    public function isPrivate(){
        if ($this->private == 1) return true;
        return false;
    }
}
