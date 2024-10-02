<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Title;
use Livewire\Component;


#[Title('Forget Password-Friday') ]
class ForgetPasswordPage extends Component {
	public $email;
	public function render() {
		return view( 'livewire.auth.forget-password-page' );
	}
	public function forgetPassword() {
		$this->validate( [ 
			'email' => [ 'required', 'email', 'exists:users', 'max:255' ]
		] );
		$status = Password::sendResetLink( [ 'email' => $this->email ] );
		if ( $status === Password::RESET_LINK_SENT ) {
			session()->flash( 'success', 'Password reset link has been sent to your email address' );
			$this->email = '';
		} else {
			session()->flash( 'error', 'Sorry error occured, Please try again later' );
		}
	}
}
