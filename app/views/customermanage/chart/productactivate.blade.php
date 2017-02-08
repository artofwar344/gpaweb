<script type="text/javascript">
	var _selects = [ "date" ];
	var _chartOption = {
		chart: {
			renderTo: "chart",
			type: 'pie'
		},
		title: {
			text: ''
		},
		subtitle: {
			text: ''
		},
		plotOptions: {
			pie: {

			}
		},
		series: []
	};
</script>

@actions (array('title' => '商品激活数量', 'buttons' => array('export')))

@search
array('label' => '日期', 'type' => 'select', 'name' => 'date')
@endsearch


