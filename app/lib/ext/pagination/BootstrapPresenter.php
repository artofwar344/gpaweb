<?php
namespace Ca\Ext\Pagination;
class BootstrapPresenter extends \Illuminate\Pagination\BootstrapPresenter
{
	public function render($previous = '&laquo;', $next = '&raquo;')
	{
		if ($this->lastPage < 13)
		{
			$content = $this->getPageRange(1, $this->lastPage);
		}
		else
		{
			$content = $this->getPageSlider();
		}
		return $this->getPrevious($previous) . $content . $this->getNext($next);
	}
}