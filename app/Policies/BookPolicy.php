<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Book;
use App\Enums\Role;

class BookPolicy
{
    public function create(User $user): bool
    {
        return $user->role === Role::Admin;
    }

    public function update(User $user, Book $book): bool
    {
        return $user->role === Role::Admin;
    }

    public function delete(User $user, Book $book): bool
    {
        return $user->role === Role::Admin;
    }

    public function view(User $user, Book $book): bool
    {
        return true;
    }

    public function viewAny(User $user): bool
    {
        return true;
    }
}
