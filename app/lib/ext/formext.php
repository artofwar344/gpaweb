<?php

class FormExt extends Form
{
	protected static function action($action, $https)
	{
		$uri = (is_null($action)) ? Request::path() : $action;
		//str_replace(URL::base(), "", URL::current())
		return HTML::entities(URL::to($uri, $https));
	}
}
