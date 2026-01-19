<?php

namespace App\Policies;

use App\Enums\Auth\PermissionType;
use App\Models\Genre;
use App\Models\User;

class GenrePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can(PermissionType::GENRE_ACCESS->value);
    }

    /**
     * Determine whether the user can create models.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->can(PermissionType::GENRE_MANAGE->value);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Genre $genre)
    {
        return $genre->deleted_at === null
            && $user->can(PermissionType::GENRE_MANAGE->value);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Genre $genre)
    {
        return $genre->deleted_at === null
            && $user->can(PermissionType::GENRE_MANAGE->value);
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Genre $genre)
    {
        return $genre->deleted_at !== null
            && $user->can(PermissionType::GENRE_MANAGE->value);
    }
}
