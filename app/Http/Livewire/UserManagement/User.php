<?php

namespace App\Http\Livewire\UserManagement;

use App\Models\Role;
use App\Models\Team;
use App\Models\User as ModelsUser;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class User extends Component
{
    public $users_id;
    public $role_id;
    public $team_id = 1;
    public $name;
    public $email;
    public $password;


    public $form_active = false;
    public $form = false;
    public $update_mode = false;
    public $modal = true;

    public function render()
    {
        $roles = Role::whereIn('role_type', ['admin'])->get();
        return view('livewire.usermanagement.users', [
            'items' => ModelsUser::whereHas('roles', function ($query) {
                return $query->where('role_type', 'admin');
            })->get(),
            'roles' => $roles
        ]);
    }

    public function store()
    {
        $this->_validate();
        $role_type = Role::find($this->role_id)->role_type;
        $user = ModelsUser::create([
            'name'  => $this->name,
            'email'  => $this->email,
            'password'  => Hash::make($role_type . '123')
        ]);

        $team = Team::find($this->team_id);
        $team->users()->attach($user, ['role' => $role_type]);
        $user->roles()->attach($this->role_id);

        $this->_reset();
        return $this->emit('showAlert', ['msg' => 'Data Berhasil Disimpan']);
    }

    public function update()
    {
        $this->_validate();
        $user = ModelsUser::find($this->users_id);
        $role_type = Role::find($this->role_id)->role_type;
        $user->update([
            'name'  => $this->name,
            'email'  => $this->email,
            'password'  => Hash::make($role_type . '123')
        ]);

        $user->teams()->sync($this->team_id);
        $user->roles()->sync($this->role_id);
        $this->_reset();
        return $this->emit('showAlert', ['msg' => 'Data Berhasil Diupdate']);
    }

    public function delete()
    {
        $user = ModelsUser::find($this->users_id);
        $user->teams()->detach();
        $user->roles()->detach();
        $user->delete();

        $this->_reset();
        return $this->emit('showAlert', ['msg' => 'Data Berhasil Dihapus']);
    }

    public function _validate()
    {
        $rule = [
            'name'  => 'required',
            'email'  => 'required',
            'role_id'  => 'required'
        ];

        return $this->validate($rule);
    }

    public function getDataById($users_id)
    {
        $users = ModelsUser::find($users_id);
        $this->users_id = $users->id;
        $this->name = $users->name;
        $this->email = $users->email;
        $this->role_id = $users->role->id;
        $this->password = $users->password;
        if ($this->form) {
            $this->form_active = true;
            $this->emit('loadForm');
        }
        if ($this->modal) {
            $this->emit('showModal');
        }
        $this->update_mode = true;
    }

    public function getId($users_id)
    {
        $users = ModelsUser::find($users_id);
        $this->users_id = $users->id;
    }

    public function toggleForm($form)
    {
        $this->form_active = $form;
        $this->emit('loadForm');
    }

    public function showModal()
    {
        $this->emit('showModal');
    }

    public function _reset()
    {
        $this->emit('closeModal');
        $this->users_id = null;
        $this->role_id = null;
        $this->name = null;
        $this->email = null;
        $this->password = null;
        $this->form = false;
        $this->form_active = false;
        $this->update_mode = false;
        $this->modal = true;
    }
}
