<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;


#[Title('Reset Password-Friday') ]
class ResetPasswordPage extends Component {

	public $token;
	#[Url ]
	public $email;
	public $password;
	public $password_confirmation;


	public function mount( $token ) {
		$this->token = $token;
	}

	public function render() {
		return view( 'livewire.auth.reset-password-page' );
	}

	public function resetPassword() {
		$this->validate( [ 
			'token' => 'required|max:255',
			'email' => 'required|email|exists:users|max:255',
			'password' => [ 'required', 'confirmed', Password::min( 9 ) ]
		] );
		$status = \Illuminate\Support\Facades\Password::reset( [ 
			'email' => $this->email,
			'token' => $this->token,
			'password' => $this->password,
			'password_confirmation' => $this->password_confirmation,
		], function (User $user, string $password) {
			$user->forceFill( [ 
				'password' => Hash::make( $password ),
			] );
			$user->save();
		}, );
		if ( $status == \Illuminate\Support\Facades\Password::PASSWORD_RESET ) {
			return redirect()->route( 'login' );
		} else {
			session()->flash( 'error', 'Something Went Wrong' );
		}
	}
}
