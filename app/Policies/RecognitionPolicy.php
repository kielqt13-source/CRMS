<?php

namespace App\Policies;

use App\Models\Recognition;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class RecognitionPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Recognition  $recognition
     * @return bool
     */
    public function view(User $user, Recognition $recognition): bool
    {
        return $user->id === $recognition->user_id;
    }
}