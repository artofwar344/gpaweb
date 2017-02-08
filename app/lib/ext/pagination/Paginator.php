<?php
namespace Ca\Ext\Pagination;
class Paginator extends \Illuminate\Pagination\Paginator {
	protected $anchor = null;

	public function addAnchor ($anchor)
	{
		$this->anchor = $anchor;
		return $this;
	}

	public function getUrl($page)
	{
		$parameters = array (
			$this->env->getPageName() => $page,
		);

		// If we have any extra query string key / value pairs that need to be added
		// onto the URL, we will put them in query string form and then attach it
		// to the URL. This allows for extra information like sortings storage.
		if (count($this->query) > 0)
		{
			$parameters = array_merge($parameters, $this->query);
		}

		//if there has a anchor, add it onto the URL
		if ($this->anchor != null)
		{
			return $this->env->getCurrentUrl().'?'.http_build_query($parameters, null, '&') . '#' . $this->anchor;
		}
		return $this->env->getCurrentUrl().'?'.http_build_query($parameters, null, '&');
	}
}
