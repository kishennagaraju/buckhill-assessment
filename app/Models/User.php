<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Pipeline\Pipeline;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'uuid',
        'first_name',
        'last_name',
        'is_admin',
        'email',
        'email_verified_at',
        'password',
        'avatar',
        'address',
        'phone_number',
        'is_marketing',
        'created_at',
        'updated_at',
        'last_login_at',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'id',
        'password',
        'is_admin'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'uuid' => 'string',
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'avatar' => 'string',
        'is_admin' => 'boolean',
        'is_marketing' => 'boolean',
    ];

    public function jwt_tokens()
    {
        return $this->hasMany(JwtTokens::class, 'user_id');
    }


    /**
     * Get Admin details by email address.
     *
     * @param  string  $email
     *
     * @return \App\Models\User|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model
     */
    public function getAdminUserDetailsByEmail(string $email)
    {
        return $this->newQuery()
            ->where('email', '=', $email)
            ->where('is_admin', '=', 1)
            ->firstOrFail();
    }

    /**
     * Get User details by email address.
     *
     * @param  string  $email
     *
     * @return \App\Models\User|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model
     */
    public function getUserDetailsByEmail(string $email)
    {
        return $this->newQuery()
            ->where('email', '=', $email)
            ->where('is_admin', '=', 0)
            ->firstOrFail();
    }

    /**
     * Get User details by email address.
     *
     * @param  string  $uuid
     *
     * @return \App\Models\User|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model
     */
    public function getUserDetailsByUuid(string $uuid)
    {
        return $this->newQuery()
            ->where('uuid', '=', $uuid)
            ->firstOrFail();
    }

    /**
     * Get User details by email address.
     *
     * @param  string  $email
     *
     * @return array
     */
    public function getLoginUserDetailsByEmail(string $email, int $isAdmin)
    {
        $admin = false;

        if ($this->newQuery()
            ->where('email', '=', $email)
            ->where('is_admin', '=', 1)->exists()) {
            $admin = true;
        }

        $userDetails = $this->newQuery()
            ->where('email', '=', $email)
            ->where('is_admin', '=', $isAdmin)
            ->firstOrFail();

        return [
            'userDetails' => $userDetails,
            'is_admin' => $admin
        ];
    }

    /**
     * Create a new User.
     *
     * @param  array  $data
     *
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model
     */
    public function createUser(array $data)
    {
        return $this->newQuery()->create($data);
    }

    /**
     * Update the existing user.
     *
     * @param  string  $uuid
     * @param  array    $data
     *
     * @return bool
     * @throws \Throwable
     */
    public function updateUser(string $uuid, array $data): bool
    {
        $userDetails = $this->newQuery()->where('uuid', '=', $uuid)->firstOrFail();
        return $this->newQuery()->find($userDetails->id)->updateOrFail($data);
    }

    /**
     * Update the login time of user.
     *
     * @param  int  $userId
     *
     * @return bool
     * @throws \Throwable
     */
    public function updateLastLoginOfUser(int $userId): bool
    {
        return $this->updateUser($userId, ['last_login_at' => now()]);
    }

    public function updateLastLoginOfUserByUuid(string $uuid)
    {
        return $this->newQuery()->where('uuid', '=', $uuid)->update(['last_login_at' => now()]);
    }

    /**
     * @param  string  $userUuid
     *
     * @return mixed
     */
    public function deleteUserByUuid(string $userUuid)
    {
        if ($this->newQuery()->where('uuid', '=', $userUuid)->exists()) {
            return $this->newQuery()->where('uuid', '=', $userUuid)->delete();
        }

        throw new ModelNotFoundException();
    }

    public function listNonAdminUsers()
    {
        return app(Pipeline::class)
            ->send($this->newQuery())
            ->through([
                \App\QueryFilters\User::class,
                \App\QueryFilters\Page::class,
                \App\QueryFilters\Sort::class,
                \App\QueryFilters\FirstName::class,
                \App\QueryFilters\Email::class,
                \App\QueryFilters\Phone::class,
                \App\QueryFilters\Address::class,
                \App\QueryFilters\CreatedAt::class,
                \App\QueryFilters\Marketing::class,
            ])
            ->thenReturn()
            ->paginate(\request()->has('limit') ? \request()->get('limit') : env('PAGINATION_LIMIT', 10));
    }

    public function storeJwtTokenDetailsForUser($userId, $tokenDetails)
    {
        $user = $this->newQuery()->with('jwt_tokens')->where('uuid', '=', $userId)->first();

        if (!$user->jwt_tokens->toArray()) {
            $user->jwt_tokens()->save(new JwtTokens($tokenDetails));
        } else {
            $user->jwt_tokens()->update($tokenDetails);
        }
    }
}
