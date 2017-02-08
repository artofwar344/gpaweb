<script type="text/javascript">
	var _selects = [ "keyid", "month" ];
	var _chartOption = {
		chart: {
			renderTo: "chart",
			type: 'column'
		},
		title: {
			text: ''
		},
		subtitle: {
			text: ''
		},
		xAxis: {
			categories: []
		},
		yAxis: {
			min: 0,
			allowDecimals:false,
			title: {
				text: '使用 (次)'
			}
		},
		plotOptions: {
			column: {
				pointPadding: 0.2,
				borderWidth: 0,
				dataLabels : {
					enabled : true
				}
			}
		},
		series: []
	};
</script>

@actions (array('title' => '激活情况', 'buttons' => array('export')))

@search
	array('label' => '密钥', 'type' => 'select', 'name' => 'keyid', 'values' => array()),
	array('label' => '月份', 'type' => 'select', 'name' => 'month', 'values' => array(), 'default' => null)
@endsearch
