<script type="text/javascript">
$(function() {
	$("tr.errorcode").click(function() {
		var self = $(this), solution = self.next("tr.solution");
		if (solution.length == 0) {
			$.post("/help/errorcode", {"error_id": self.attr("errorid")}, function(ret) {
				solution = $("<tr />").addClass("solution").append($("<td />").attr("colspan", 2).html(ret.solution));
				self.after(solution);
				solution.siblings("tr.solution").hide();
			}, "json");
		}
		else {
			solution.toggle();
			solution.siblings("tr.solution").hide();
		}
		return false;
	});
});
</script>
<div class="frame_1 main_content">
	<div class="frame_1_l">
		@if (View::exists('customer.' . App::make('customer')->alias . '.partials.help.menu'))
		@include('customer.' . App::make('customer')->alias . '.partials.help.menu')
		@else
		@include('customer.common.partials.help.menu')
		@endif
	</div>

	<div class="frame_1_r">
		<h1 class="header_1">错误代码</h1>
		<div class="help_content">
			<table>
				@foreach ($errorcodes as $errorcode)
				<tr class="errorcode" errorid="{{ $errorcode->errorid }}">
					<td><a href="#">{{ $errorcode->code }}</a></td>
					<td class="last_td"><a href="#">{{ $errorcode->message }}</a></td>
				</tr>
				@endforeach
			</table>
		</div>
	</div>
	<div class="clear"></div>
</div>
