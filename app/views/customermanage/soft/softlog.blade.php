<script type="text/javascript">
	var visible = @if (Input::get("inner")) false @else true @endif;
	var disabledFields = [@if (Input::get("inner")) "name" @endif];
	var defaultValues = {{ $soft ? '{ "name": "' . $soft->name . '" }' : "{}" }};
	$(function() {
		var ret = "";
		var typeValueClass = function(row) {
			switch (parseInt(row["type"])) {
				case 1:
					ret = "green";
					break;
				case 2:
					ret = "blue";
					break;
				case 3:
					ret = "red";
					break;
			}
			return ret;
		};
		var backend = $.backend({
			listParams: { "softid": "{{ $soft_id }}" },
			tableStructure: {
				eid: "logid",
				columns: [
					{ "key": "logid", "header": "编号", "class": "number" },
					{ "key": "name", "header": "软件名称", "visible": visible },
					{ "key": "type_text", "header": "类型", "valueclass": typeValueClass },
					{ "key": "createdate", "header": "时间", "class": "state" }
				]
			},
			newDisabledFields: disabledFields,
			modifyDisabledFields: disabledFields,
			actionDisabledFields: disabledFields,
			searchDefaultValues: defaultValues,
			category: "商品",
			selects: [  ],
			operators: [  ],
			modifyStructure: {},
			validateRule: {},
			validateMessages: {}
		});
	});
</script>

@actions (array('title' => ( $soft ? $soft->name : '软件记录'), 'buttons' => array()))

@search
array('label' => '名称', 'type' => 'textbox', 'name' => 'name', 'placeholder' => '软件名称')
@endsearch
