<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Livewire\Attributes\Title;
use Livewire\Component;



#[Title('Register-Friday') ]
class RegisterPage extends Component {
	public $name;
	public $email;
	public $password;

	public function render() {
		return view( 'livewire.auth.register-page' );
	}
	public function save() {
		$this->validate( [ 
			'name' => 'required|min:3|max:255',
			'email' => 'required|email|min:3|unique:users,email|max:255',
			'password' => 'required|min:3|max:255',
		] );
		// info( 'save', [ $this->name, $this->email, $this->password ] );
		$user = User::create( [ 
			'name' => $this->name,
			'email' => $this->email,
			'password' => $this->password,
		] );
		auth()->login( $user, true );
		return redirect()->intended();
	}
}
