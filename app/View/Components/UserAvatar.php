<?php

namespace App\View\Components;

use App\Models\User;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\Component;

class UserAvatar extends Component
{
    public $user;
    public $size;
    public $class;
    public $showName;

    /**
     * Create a new component instance.
     */
    public function __construct(User $user = null, int $size = 150, string $class = '', bool $showName = false)
    {
        $this->user = $user ?? Auth::user();
        $this->size = $size;
        $this->class = $class;
        $this->showName = $showName;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.user-avatar');
    }

    /**
     * Get avatar URL
     */
    public function avatarUrl(): string
    {
        return user_avatar($this->user, $this->size);
    }

    /**
     * Get user name or fallback
     */
    public function userName(): string
    {
        return $this->user ? $this->user->name : 'Guest';
    }
}
