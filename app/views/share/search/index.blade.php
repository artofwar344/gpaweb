<div class="spacer_1"></div>
<div class="frame_1 home_content">
	<div class="frame_1_l">
		<h1 class="header_1"><span>文档搜索</span></h1>
		@include('share.partials.document.list2', $data)
		@if ($data['documents']->getTotal() == 0)
		<div class="document_list">
			<div class="header_5">
				<span class="icon icon_category"></span>{{ $data['title'] }}
			</div>
			<div class="content_wrap">
				<div class="content" style="height: 150px; line-height: 150px; text-align: center;">
					对不起，未查找到相关文档
				</div>
			</div>
		</div>
		@endif

	</div>
	<div class="frame_1_r">
		@include('share.partials.side.document_rank', array('documents' => $hot_document, 'rankTitle' => '文档排行榜'))
	</div>
</div>
<div class="clear"></div>



