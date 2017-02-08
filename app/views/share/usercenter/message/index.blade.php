<script type="text/javascript" src="{{ Config::get('app.asset_url') }}scripts/share.usercenter.js"></script>

<script>
$(function() {
	$.shareUserCenter({
		deleteUrl: "/usercenter/message/delete",
		emptyRow: '<tr class="none"><td colspan="3"><span>你没有收到消息</span></td></tr>'
	});

});

</script>

<div class="dialog_1" id="dialogDelete">
	<div class="header"><span>删除消息</span><a class="close"></a></div>
	<div class="confirm">是否删除选择的消息?</div>
	<div class="actions">
		<a href="#" class="button_1 submit"><span>确定</span></a>
		<a class="close" href="#">取消</a>
	</div>
</div>

<div class="my_message">
	<ul class="tabsheet_2">
		<li class="selected"><a>系统消息</a></li>
		<li class="info"><span>共 <strong>{{ $messages->getTotal() }}</strong> 条</span></li>
	</ul>
	<div class="spacer_1"></div>
	<div class="information_1" style="display:none"><div class="success"></div><span class="close"></span></div>
	<div class="main_actions">
		<a class="button_3 button_3_disabled btn_3_del_file" href="#"><span>删除</span></a>
		<div class="clear"></div>
	</div>
	<table class="table_1">
		<tr>
			<th style="text-align:left" colspan="2">消息内容</th>
			<th style="width:80px; text-align:right">发送时间</th>
		</tr>
		@foreach ($messages as $message)
		<tr type="1" eid="{{ $message->messageid }}">
			<td class="check">
				<input class="check" type="checkbox" value="{{ $message->messageid }}" />
			</td>
			<td style="text-align:left">
					<span class="title">
						<span>
							<?php
							switch ($message->type)
							{
								case \Ca\MessageType::getNewAnswer:
									echo '新回答';
									break;
								case \Ca\MessageType::acceptAnswer:
									echo '问者采纳';
									break;
								case \Ca\MessageType::moreAnswer:
									echo '新追问';
									break;
								case \Ca\MessageType::updateQuestion:
									echo '新修正';
									break;
								default:
									break;
							}
							?>
						</span>
						<a target="_blank" href="{{ url('/knows/question?id=' . $message->content->questionid) }}">
							{{ $message->content->question_title }}
						</a>
					</span>
			</td>
			<td style="text-align:right">{{ Ca\Common::datetime_to_date($message->createdate, 'Y-m-d') }}</td>
		</tr>
		@endforeach

		@if ($messages->getTotal() == 0)
		<tr class="none">
			<td colspan="3">
				<span>你没有收到消息</span>
			</td>
		</tr>
		@endif
	</table>
	@if ($messages->getLastPage() > 1)
		{{ $messages->links() }}
	@endif
</div>

