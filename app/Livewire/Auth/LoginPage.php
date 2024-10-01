<?php

namespace App\Livewire\Auth;

use Livewire\Attributes\Title;
use Livewire\Component;


#[Title('Login-Friday') ]
class LoginPage extends Component {
	public $email;
	public $password;

	public function render() {
		return view( 'livewire.auth.login-page' );
	}
	public function save() {
		$this->validate( [ 
			'email' => 'required|min:3|max:255|email|exists:users',
			'password' => 'required|min:3|max:255'
		] );
		// info( 'login', [ $this->email, $this->password ] );
		$success = auth()->attempt( [ 
			'email' => $this->email,
			'password' => $this->password,
		] );
		if ( $success ) {
			request()->session()->regenerate();
			return redirect()->intended( '/' );
		} else {
			session()->flash( 'error', 'Wrong email or password' );
			return;
		}
	}
}
