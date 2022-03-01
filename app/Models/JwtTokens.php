<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JwtTokens extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'unique_id',
        'token_title',
        'restrictions',
        'permissions',
        'expires_at',
        'last_used_at',
        'refreshed_at',
        'created_at',
        'updated_at',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'last_used_at' => 'datetime',
        'refreshed_at' => 'datetime',
        'expires_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @param  string  $token
     *
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model
     */
    public function getJwtTokenDetails(string $token, array $relationShips = [])
    {
        $query = $this->newQuery();

        if ($relationShips) {
            $query->with($relationShips);
        }

        return $query->where('unique_id', '=', $token)->firstOrFail();
    }

    /**
     * @param  string  $token
     * @param  array   $data
     *
     * @return bool
     */
    public function updateJwtToken(string $token, array $data = [])
    {
        if ($data) {
            $this->newQuery()->where('unique_id', '=', $token)->firstOrFail()->update($data);
        }

        return true;
    }
}
