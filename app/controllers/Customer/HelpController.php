<?php
namespace Customer;

use Illuminate\Support\Facades\Redirect;
use View,
	Request,
	InputExt,
	Ca\Service\ParamsService,
	Ca\Service\HelpService,
	Ca\Service\ActivationerrorService;

class HelpController extends BaseController
{
	public $layout = 'layouts.page';
	public function client()
	{
		$categories = HelpService::get_category_info();
		$clientpublishversion = ParamsService::get('clientpublishversion');
		$clientversion = ParamsService::get($clientpublishversion == 3 ? 'clientversion3' : 'clientversion');
		$this->view($clientpublishversion == 3 ? 'help.client3' : 'help.client')
			->with('categories', $categories)
			->with('clientversion', $clientversion);
	}

	public function u()
	{
		$this->view('help.u');
	}
	public function sp1()
	{
		
		$this->view('help.win7_sp1');
	}
	public function office_error()
	{
		
		$this->view('help.office_error');
	}
	public function  activate_error()
	{
		
		$this->view('help.activate_error');
	}
	public function  uninstall()
	{
		
		$this->view('help.uninstall');
	}


	public function kms()
	{
		$this->view('help.kms');
	}

	public function errorcode()
	{
		if (Request::ajax() && $errorId = InputExt::getInt('error_id'))
		{
			$error = ActivationerrorService::get($errorId);
			print json_encode(array('solution' => $error->solution));
			exit;
		}

		$errorcodes = ActivationerrorService::all();
		$this->view('help.errorcode')->with('errorcodes', $errorcodes);
	}

	public function faq()
	{
		$categories = HelpService::get_category_info();
		$this->view('help.faq')->with('categories', $categories);
	}

	public function faqdetail($alias, $categoryid)
	{

		$categories = HelpService::get_category_info();
		$arrs = array();
		foreach($categories as $category)
		{
			$arrs[] = $category->categoryid;
		}
		if(in_array($categoryid, $arrs))
		{
			$helps = HelpService::get_help_detail($categoryid);
			$this->view('help.help')
				->with('helps', $helps)
				->with('categories', $categories);
		} else {
			return Redirect::to('/');
		}
	}

}