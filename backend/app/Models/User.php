<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * Função para verificar se o usuário possui um certo perfil.
     */

    public function hasProfile(string $profileName): bool
    {
        return $this->profiles()->where('name', $profileName)->exists();
    }

    /**
     * Função para verificar se o usuário possui um certo perfil por ID.
     */
    public function hasProfileById(int $profileId): bool
    {
        return $this->profiles()->where('id', $profileId)->exists();
    }

    /**
     * Função para obter todos os IDs dos perfis do usuário.
     */
    public function getProfileIds(): array
    {
        return $this->profiles()->pluck('id')->toArray();
    }

    public function profiles()
    {
        return $this->belongsToMany(Profile::class);
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
