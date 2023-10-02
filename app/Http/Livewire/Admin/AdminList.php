<?php

namespace App\Http\Livewire\Admin;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Response;
use Livewire\Component;

class AdminList extends Component
{
    public function delete(User $admin)
    {
        abort_if(!auth()->user()->is_admin, Response::HTTP_FORBIDDEN, 403);

        $admin->delete();
    }

    public function render(): View
    {
        $admins = User::admin()->paginate();

        return view('livewire.admin.admin-list', [
            'admins' => $admins
        ]);
    }
}
