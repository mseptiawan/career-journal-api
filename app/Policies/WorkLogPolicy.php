<?php

namespace App\Policies;

use App\Models\User;
use App\Models\WorkLog;

class WorkLogPolicy
{
    public function view(User $user, WorkLog $workLog): bool
    {
        return $user->id === $workLog->user_id;
    }

    public function update(User $user, WorkLog $workLog): bool
    {
        return $user->id === $workLog->user_id;
    }

    public function delete(User $user, WorkLog $workLog): bool
    {
        return $user->id === $workLog->user_id;
    }
}
