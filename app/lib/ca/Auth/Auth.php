<?php
namespace Ca\Auth;

use Illuminate\Auth\UserProviderInterface,
	Illuminate\Auth\UserInterface,
	Illuminate\Auth\GenericUser,
	Session,
	DB,
	Config;

class Auth implements UserProviderInterface {

	protected $providers;

	public function __construct($userService=NULL)
	{
		$this->userService = $userService;
		// This should be moved to the config later...
		// This is a list of providers that can be used, including
		// their user model, hasher class, and hasher options...
		$this->providers = Config::get('auth.providers');
	}
	/**
	 * Retrieve a user by their unique identifier.
	 *
	 * @param  mixed  $identifier
	 * @return \Illuminate\Auth\UserInterface|null
	 */
	public function retrieveById($identifier)
	{
		// Returns the current provider from the session.
		// Should throw an error if there is none...
		$provider = Session::get('user.provider');
		if (empty($provider))
		{
			return null;
		}

		$query = $this->createModel($this->providers[$provider]['model'])->newQuery();//->find($identifier);
//		$user = DB::table($this->providers[$provider]['table'])
//			->select(array('*'))
//			->where($this->providers[$provider]['pkey'], $identifier)
//			->first();
		if (isset($this->providers[$provider]['joins']))
		{
			foreach ($this->providers[$provider]['joins'] as $key => $value)
			{
				$query->leftJoin($value['table'], $value['colum1'], '=', $value['colum2']);
			}
		}

		if (isset($this->providers[$provider]['selects']))
		{
			$query->select($this->providers[$provider]['selects']);
		}
		$user = $query->find($identifier);

		if ($user)
		{
			$user->provider = $provider;
		}
		if($user->provider == 'customer.manager')
		{
			$entity = DB::table('department')
				->select(array('departmentid', 'name', 'parentid'))
				->where('departmentid', '=', $user->departmentid)
				->first();

			$user->departmentname = $entity->name;
			$user->top = is_null($entity->parentid);
		}
		return $user;
	}

	/**
	 * Retrieve a user by the given credentials.
	 *
	 * @param  array  $credentials
	 * @return \Illuminate\Auth\UserInterface|null
	 */
	public function retrieveByCredentials(array $credentials)
	{
		// First we will add each credential element to the query as a where clause.
		// Then we can execute the query and, if we found a user, return it in a
		// Eloquent User "model" that will be utilized by the Guard instances.

		// Retrieve the provider from the $credentials array.
		// Should throw an error if there is none...
		$provider = $credentials['provider'];

		$query = $this->createModel($this->providers[$provider]['model'])->newQuery();
		//$query = DB::table($this->providers[$provider]['table']);

		foreach ($credentials as $key => $value)
		{
			if ( ! str_contains($key, 'password') && ! str_contains($key, 'provider'))
			{
				$query->where($key, $value);
			}
		}

		$user = $query->first();

		if ($user)
		{
			Session::put('user.provider', $provider);
			$user->provider = $provider;
		}

		return $user;
	}

	/**
	 * Validate a user against the given credentials.
	 *
	 * @param  \Illuminate\Auth\UserInterface  $user
	 * @param  array  $credentials
	 * @return bool
	 */
	public function validateCredentials(UserInterface $user, array $credentials)
	{
		$plain = $credentials['password'];

		// Retrieve the provider from the $credentials array.
		// Should throw an error if there is none...
		$provider = $credentials['provider'];

		$options = array();

		if (isset($this->providers[$provider]['options']))
		{
			foreach ($this->providers[$provider]['options'] as $key => $value)
			{
				$options[$key] = $user->$value;
			}
		}

		if (array_key_exists('hasher', $this->providers[$provider]))
		{
			$hasher = $this->providers[$provider]['hasher'];
			if (is_callable($hasher))
			{
				return call_user_func($hasher, $plain) == $user->getAuthPassword();
			}
			if (class_exists($hasher))
			{
				$hasherObj = new $hasher;
				return $hasherObj->check($plain, $user->getAuthPassword(), $options);
			}
		}
		return \Hash::check($plain, $user->getAuthPassword(), $options);
	}

	/**
	 * Create a new instance of a class.
	 *
	 * @param string $name Name of the class
	 * @return Class
	 */
	public function createModel($name)
	{
		$class = '\\'.ltrim($name, '\\');

		return new $class;
	}

}