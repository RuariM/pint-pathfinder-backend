<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Path extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'startpoint_name',
        'startpoint_lat',
        'startpoint_long',
        'endpoint_name',
        'endpoint_lat',
        'endpoint_long',
        'is_public',
    ];

    public function setVenues(array $ids)
    {
        //venues should be passed in stop order
        $syncData = [];

        foreach (range(0, count($ids) - 1) as $index) {
            $syncData[$ids[$index]] = ['stop_number' => $index];
        }

        $this->venues()->sync($syncData);
        return $this;
    }

    public function setCreator(int $id)
    {
        $this->users()->sync(
            [$id => ["is_creator" => true]]
        );
    }

    //should this be called update participants
    public function setParticipant(int $user_id, bool $remove)
    {
        $user = $this->users()->find($user_id, ['user_id']);

        //if creator dont do anything
        if ($user && $user->pivot->is_creator) return 'provided user is path creator';

        //remove user from path
        if ($remove && $user)
            $this->users()->detach($user_id);
        //else add user to path if they are not already added.
        else if (!$user) $this->users()->attach($user_id);

        return $this;
    }

    public function setCompleted(array $user_id) //pass
    {
        $this->users()->updateExistingPivot([$user_id], ["set_completed" => true]);
    }

    public function venues()
    {
        return $this->belongsToMany(Venue::class)
            ->withPivot(['stop_number']);
    }

    public function users()
    {
        return $this->belongsToMany(User::class)
            ->withPivot(['is_creator', 'has_completed']);
    }

    public function ratings()
    {
        return $this->morphMany(Rating::class, 'rateable');
    }
}
