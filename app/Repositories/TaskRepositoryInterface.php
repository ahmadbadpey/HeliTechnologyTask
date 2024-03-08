<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Collection;

interface TaskRepositoryInterface
{
    public function all(): Collection;


    public function findByUser($user) : Collection;
}
