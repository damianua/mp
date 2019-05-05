<?php


namespace App\Policies;


use App\User;

class AbstractPolicy
{
	public function before(User $user, $ability)
	{
		if($user->isAdmin()){
			return true;
		}
	}
}