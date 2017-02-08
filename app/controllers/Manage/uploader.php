<?php

class Uploader_Controller extends Controller {

	public function action_login()
	{
		if (Request::method() != "POST") exit("");
		$input = Input::all();
		$login = $input["name"];
		$password = $input["password"];
		$key = $input["key"];
		if ($key !== 'fgnrhSERGQ#$^@#$TFWEf') exit("");;
		$credentials = array('username' => $login, 'password' => $password);

		if (Auth::attempt($credentials))
		{
			if (Auth::user()->status != 1)
			{
				Auth::logout();
				echo Response::json(array('status' => -3));
			}
			echo Response::json(array('status' => 1, 'id' => Auth::user()->adminerid));
		}
		else
		{
			echo Response::json(array('status' => -2));
		}
		exit;
	}
}