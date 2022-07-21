<?php

namespace App\Http\Livewire\Auth;

use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\WithFileUploads;

class Register extends Component
{
    use WithFileUploads;

    public $name;
    public $email;
    public $nomor_hp;
    public $alamat;
    public $password;
    public $password_confirmation;


    public function render()
    {
        return view('livewire.auth.register');
    }

    public function store()
    {
        // dd('ok');
        $rules = [
            'name' => 'required',
            'email' => 'required|email',
            'nomor_hp' => 'required',
            'alamat' => 'required',
            'password' => 'required|min:6|regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x])(?=.*[!$#%.@]).*$/',
            'password_confirmation' => 'required|min:6|same:password',
        ];
        $role_member = Role::where('role_type', 'member')->first();
        $this->validate($rules, [
            'required' => ':attribute tidak boleh kosong',
            'min' => ':attribute minimal :min karakter',
            'same' => ':attribute tidak sama dengan :other',
            'regex' => ':attribute harus mengandung angka, huruf, dan simbol',
            'email' => ':attribute tidak valid',
        ]);

        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,

            'password' => Hash::make($this->password)
        ]);

        $user->userDetail()->create([
            'nomor_hp' => $this->nomor_hp,
            'alamat' => $this->alamat,
        ]);
        $user->roles()->attach($role_member->id);
        $user->teams()->attach(1, ['role' => $role_member->role_type]);

        $this->_resetForm();
        event(new Registered($user));
        $this->emit('showAlert', [
            'msg' => 'Registrasi Berhasil, silahkan login.',
            'redirect' => true,
            'path' => 'login'
        ]);
    }

    public function _resetForm()
    {
        $this->name = null;
        $this->email = null;
        $this->password = null;
        $this->password_confirmation = null;
    }
}
