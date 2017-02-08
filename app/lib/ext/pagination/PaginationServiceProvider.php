<?php
namespace Ca\Ext\Pagination;

class CaPaginationProvider extends \Illuminate\Pagination\PaginationServiceProvider {
	public function register()
	{
		$this->app['paginator'] = $this->app->share(function($app)
		{
			$paginator = new Environment($app['request'], $app['view'], $app['translator']);

			$paginator->setViewName($app['config']['view.pagination']);

			return $paginator;
		});
	}
}