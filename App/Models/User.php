<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
// Importaciones necesarias para la autenticación por Active Directory
use LdapRecord\Laravel\Auth\LdapAuthenticatable;
use LdapRecord\Laravel\Auth\HasLdapUser;

class User extends Authenticatable implements LdapAuthenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasLdapUser;

    /**
     * Los atributos que se pueden asignar masivamente.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'guid',   // ID único asignado por el Active Directory de la USC
        'domain', // El nombre del dominio al que pertenece el usuario
    ];

    /**
     * Los atributos que deben ocultarse para la serialización.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Los atributos que deben ser casteados.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Implementación Forzosa de Métodos LDAP
    |--------------------------------------------------------------------------
    | Estos métodos le indican a LdapRecord cómo mapear y persistir 
    | las variables de sesión del dominio en tu base de datos local.
    */

    public function getLdapGuidColumn(): string
    {
        return 'guid';
    }

    public function getLdapDomainColumn(): string
    {
        return 'domain';
    }

    public function getLdapGuid(): ?string
    {
        return $this->guid;
    }

    public function setLdapGuid(?string $guid): void
    {
        $this->guid = $guid;
    }

    public function getLdapDomain(): ?string
    {
        return $this->domain;
    }

    public function setLdapDomain(?string $domain): void
    {
        $this->domain = $domain;
    }
}