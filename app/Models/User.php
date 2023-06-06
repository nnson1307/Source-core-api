<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get customer pagination data
     *
     * @param array $filter
     *
     * @return void
     */
    public function getList($filter = [])
    {
        //Get page
        $page = $filter['page'] ?? 1;
        //Get perpage
        $perpage = $filter['perpage'] ?? 10;

        if (empty($filter['search'])) {
            $filter['search'] = '';
        }

        return $this
            ->where("name", 'like', '%' . $filter['search'] . '%')
            ->orderBy("{$this->table}.id", "desc")
            ->paginate($perpage, $columns = ['*'], $pageName = 'page', $page);
    }
}
