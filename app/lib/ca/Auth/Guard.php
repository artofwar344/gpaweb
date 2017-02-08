<?php
namespace Ca\Auth;
use Illuminate\Auth\UserInterface;

class Guard extends \Illuminate\Auth\Guard {

	public function login(UserInterface $user, $remember = false, $provider = 'user')
	{
		$id = $user->getAuthIdentifier();
		$this->session->put('user.provider', $provider);
		$this->session->put($this->getName(), $id);

		// If the user should be permanently "remembered" by the application we will
		// queue a permanent cookie that contains the encrypted copy of the user
		// identifier. We will then decrypt this later to retrieve the users.
		if ($remember)
		{
			$this->queueRecallerCookie($id);
		}

		// If we have an event dispatcher instance set we will fire an event so that
		// any listeners will hook into the authentication events and run actions
		// based on the login and logout events fired from the guard instances.
		if (isset($this->events))
		{
			$this->events->fire('auth.login', array($user, $remember));
		}

		$this->setUser($user);
	}

}