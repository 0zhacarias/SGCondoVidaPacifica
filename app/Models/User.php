<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Responsavel;
use DateTimeInterface;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Auth;

class User extends Authenticatable
{
    use HasRoles;
    protected $table="users";
    protected $guarded=['id'];
    use HasApiTokens, HasFactory, Notifiable,SoftDeletes;
/**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'telefone',
        'username',
        'roles_id',
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
     * The attributes that are mass assignable.
     *
     * @var array
     */


    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $appends = ['all_permissions','can'];


    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */


    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d');
    }
    

    public function getAllPermissionsAttribute()
    {
        return $this->getAllPermissions();
    }


     /**
     * Get all user permissions in a flat array.
     *
     * @return array
     */
    public function getCanAttribute()
    {
        $permissions = [];
        foreach (Permission::all() as $permission) {

            if(Auth::user())
            if (Auth::user()->can($permission->name)) {
                $permissions[$permission->name] = true;
            } else {
                $permissions[$permission->name] = false;
            }
        }
        return $permissions;
    }

 



public function responsavel()
{
    return $this->hasOne(Pessoa::class);
    # code...
}
public function notificacao()
{
    return $this->hasMany(User::class);
    # code...
}
public function role(){
    return $this->belongsTo(Role::class);
}
    
}
