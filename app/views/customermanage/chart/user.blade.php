<script type="text/javascript">
	var _selects = [ "years" ];
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
				text: '数量 (人)'
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

@actions (array('title' => '注册用户统计', 'buttons' => array('export')))

@search
array('label' => '年份', 'type' => 'select', 'name' => 'years', 'default' => null),
array('label' => '统计方式', 'type' => 'select', 'name' => 'type', 'values' => array('1' => '新增', '2' => '总量'), 'default' => null)
@endsearch


