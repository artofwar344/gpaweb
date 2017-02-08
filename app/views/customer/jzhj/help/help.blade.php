<div class="frame_1 main_content">
	<div class="frame_1_l">
		@if (View::exists('customer.' . App::make('customer')->alias . '.partials.help.menu'))
		@include('customer.' . App::make('customer')->alias . '.partials.help.menu')
		@else
		@include('customer.common.partials.help.menu')
		@endif
	</div>

	<div class="frame_1_r">
		@if($helps)
			 @foreach($helps as $help)

				<h1 class="header_1">{{$help->title}}</h1>
				<div class="help_content">
					<p>{{$help->content}}</p>
				</div>
			 @endforeach
		@endif
	</div>
	<div class="clear"></div>
</div>
