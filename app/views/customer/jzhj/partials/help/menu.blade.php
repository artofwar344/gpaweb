{? $uri = Request::path(); ?}
<div class="product_menu">
	<h1>使用帮助</h1>
	<div class="list">
		<a href="/help/client" style="background-image:url({{ Config::get('app.asset_url') . 'images/customer/common/help/gpclient_logo.png' }})"
		   class="item @if($uri == 'help/client') hot @endif">
			GP激活客户端{{ \Ca\Service\ParamsService::get('clientpublishversion') == 3 ? '3.0' : '' }}
		</a>
	</div>
	<div class="list">
		<a href="/help/faq" style="background-image:url({{ Config::get('app.asset_url') . 'images/customer/common/help/question.png' }})"
		   class="item @if($uri == 'help/faq') hot @endif">常见问题</a>
	</div>
	@if($categories)
		<div class="list">
			@foreach($categories as $category)
					<a href="/help/faq/{{$category->categoryid}}"
					   style="background-image:url({{ Config::get('app.asset_url') . 'images/customer/common/help/question.png' }})"
					   class="item @if($uri == 'help/faq/'.$category->categoryid) hot @endif">{{$category->name}}
					</a>
			@endforeach
		</div>
	@endif
</div>
