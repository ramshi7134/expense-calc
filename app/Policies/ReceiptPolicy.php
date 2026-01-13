<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Receipt;

class ReceiptPolicy
{
    public function view(User $user, Receipt $receipt)
    {
        return $user->id === $receipt->user_id;
    }

    public function update(User $user, Receipt $receipt)
    {
        return $user->id === $receipt->user_id;
    }
}
