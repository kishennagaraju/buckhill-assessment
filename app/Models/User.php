<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
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
    protected $hidden = [];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_admin' => 'boolean',
        'is_marketing' => 'boolean',
    ];

    /**
     * Get User details by email address.
     *
     * @param  string  $email
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function getAdminUserDetailsByEmail(string $email): \Illuminate\Database\Eloquent\Builder
    {
        return $this->newQuery()
            ->where('email', '=', $email)
            ->where('is_admin', '=', 1);
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
     * @param  integer  $id
     * @param  array    $data
     *
     * @return bool
     * @throws \Throwable
     */
    public function updateUser(int $id, array $data): bool
    {
        return $this->newQuery()->find($id)->updateOrFail($data);
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

    /**
     * @param  string  $userUuid
     *
     * @return mixed
     */
    public function deleteUserByUuid(string $userUuid)
    {
        return $this->newQuery()
            ->where('uuid', '=', $userUuid)
            ->where('is_admin', '=', 0)
            ->delete();
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
}
