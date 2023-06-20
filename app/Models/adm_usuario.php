<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class adm_usuario extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;

     /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'us_login',
        'us_contrasenia',
        'us_nombre',
        'us_login_crea',
        'us_fecha_crea',
        'us_fecha_estado',
        'us_estado',
        'us_empleado',
        'us_terminal',
        'us_mail',
        'us_departamento',
        'us_cargo',
        'us_direccion',
        'us_cedula',
        'us_cambio_clave',
        'us_cod_activa',

    ];
    protected $guarded = ['us_login'];
    protected $primaryKey = 'us_login';
    protected $table = 'adm_usuario';
    protected $connection = '';
    public $timestamps = false;

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
    ];


    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getAuthPassword()
    {
        return $this->us_contrasenia;
    }

    public function validateCredentials(array $credentials)
    {
        $plain = $credentials['password'];
        return $this->hasher->check($plain, $this->getAuthPassword());
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [
            'name' => $this->us_nombre,
        ];
    }

}
