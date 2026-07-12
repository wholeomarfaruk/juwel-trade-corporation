<?php

namespace App\Livewire\Admin\Account;

use App\Models\Media;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\Component;

class AccountSettings extends Component
{
    public string $name = '';
    public string $email = '';
    public string $currentPassword = '';
    public string $newPassword = '';
    public string $newPasswordConfirmation = '';
    public ?int $avatarMediaId = null;

    public function mount(): void
    {
        $user = auth()->user();
        $this->name = $user->name;
        $this->email = $user->email;
        $this->avatarMediaId = $user->avatar_media_id;
    }

    protected function profileRules(): array
    {
        return [
            'name'          => ['required', 'string', 'max:255'],
            'email'         => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore(auth()->id())],
            'avatarMediaId' => ['nullable', 'exists:media,id'],
        ];
    }

    protected function passwordRules(): array
    {
        return [
            'currentPassword'          => ['required'],
            'newPassword'              => ['required', 'string', 'min:8', 'same:newPasswordConfirmation'],
            'newPasswordConfirmation'  => ['required'],
        ];
    }

    public function updateProfile(): void
    {
        $this->validate($this->profileRules());

        auth()->user()->update([
            'name'            => $this->name,
            'email'           => $this->email,
            'avatar_media_id' => $this->avatarMediaId,
        ]);

        $this->dispatch('toast', [
            'title'   => 'Success',
            'message' => 'Profile updated successfully.',
            'icon'    => 'success',
        ]);
    }

    public function updatePassword(): void
    {
        $this->validate($this->passwordRules());

        $user = auth()->user();

        if (!Hash::check($this->currentPassword, $user->password)) {
            $this->addError('currentPassword', 'The current password is incorrect.');
            return;
        }

        $user->update(['password' => Hash::make($this->newPassword)]);

        $this->currentPassword         = '';
        $this->newPassword             = '';
        $this->newPasswordConfirmation = '';
        $this->resetValidation(['currentPassword', 'newPassword', 'newPasswordConfirmation']);

        $this->dispatch('toast', [
            'title'   => 'Success',
            'message' => 'Password updated successfully.',
            'icon'    => 'success',
        ]);
    }

    public function render()
    {
        return view('livewire.admin.account.account-settings', [
            'user'         => auth()->user(),
            'avatarMedia'  => $this->avatarMediaId ? Media::find($this->avatarMediaId) : null,
        ]);
    }
}
