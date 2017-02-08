<?php
$presenter = new Ca\Ext\Pagination\BootstrapPresenter($paginator);

if ($paginator->getLastPage() > 1): ?>
<div class="pagination">
	<ul>
		<?php echo $presenter->render('上一页', '下一页'); ?>
	</ul>
</div>
<?php endif; ?>
