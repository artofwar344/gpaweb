<script type="text/javascript">
	$(function() {
		$(".tab").hide().eq(0).show();
		$(".tabsheet_1").find("a").click(function() {
			$(".tab").hide().eq($(this).index()).show();
			$(this).addClass("hot").siblings().removeClass("hot");
			return false;
		});

		var downloads = {{ json_encode($product['subversion']) }};

		$(".download select").change(function() {
			var id = $("option:selected", $(this)).index();
			var size = downloads[id]["size"];
			var md5 = downloads[id]["md5"];
			$(".download .size").text(size);
			$(".download .md5").text(md5);
		}).change();
		$(".btn_submit").click(function() {
			$(".download").submit();
			return false;
		});
	});
</script>
<div class="product_banner"></div>
<div class="frame_1 main_content">
	<div class="frame_1_l">
		@if (View::exists('customer.' . App::make('customer')->alias . '.partials.download.productmenu'))
		@include('customer.' . App::make('customer')->alias . '.partials.download.productmenu')
		@else
		@include('customer.common.partials.download.productmenu')
		@endif
	</div>
	<div class="frame_1_r">
		<h1 class="header_1">{{ $product['name'] }}</h1>
		<div class="content product_main">
			<img src="{{ Config::get('app.asset_url') . $product['img'] }}">
			<form class="download" target="_blank" method="POST" action="/download/file">
				<input type="hidden" name="name" value="{{ $productName }}" />
				<h2>选择 {{ $product['name'] }} 版本: </h2>
				<ul>
					<li>
						<label>系统架构:</label>
						<select name="bit" class="select_1">
							@foreach ($product['subversion'] as $i => $subversion)
							<option value="{{ $i }}">{{ $subversion['title'] }}</option>
							@endforeach
						</select>
					</li>
					<li>
						<label>软件大小:</label> <span class="size"></span>
					</li>
					<li><label>MD5校验码:</label> <span class="md5"></span></li>
					<li>
						@if (\Ca\Service\ParamsService::get('login2downloadproduct') != 1)
						<a href="#" class="button_2 btn_submit">立即下载</a>
						@else
						<a href="{{ Ca\Common::link_to_login() }}" class="button_2">立即下载</a>
						@endif
					</li>
				</ul>
			</form>
			<div class="clear"></div>
		</div>
		<div class="tabsheet_1">
			<a class="intro hot" href="#"><span class="fixpng">商品介绍</span></a>
			<a class="requirement" href="#"><span class="fixpng">配置要求</span></a>
			<a class="download" href="#"><span class="fixpng">安装步骤</span></a>
		</div>
		<div class="clear"></div>
		<div class="tabsheet_1_tabs">
			<div class="content tab">
				@include('customer.common.download.introduction.' . $productName)
			</div>
			<div class="content product_required tab">
				@include('customer.common.download.requirement.' . $productName)
			</div>
			<div class="content download_page tab">
				@include('customer.common.download.install.' . $productName)
			</div>
		</div>
	</div>
	<div class="clear"></div>
</div>