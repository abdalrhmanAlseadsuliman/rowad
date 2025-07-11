<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Book;

class BookPolicy
{

    public function create(User $user): bool
    {
        return $user->role === 'admin';
    }


    public function update(User $user, Book $book): bool
    {
        return $user->role === 'admin';
    }



    public function delete(User $user, Book $book): bool
    {
        return $user->role == 'admin';
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
