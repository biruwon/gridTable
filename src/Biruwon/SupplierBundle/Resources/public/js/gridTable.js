$(function() {

	var grid = $('#gridTable');
	grid.jqGrid({
		url: path,
		datatype: 'json',
		height: 'auto',
        colNames:['Index' ,'Product','Units Sold', 'Total Cost', 'Total Revenue','Total Profit'],
        colModel:[
			{name:'id', index:'id', width:60, sorttype:"int", hidden: true, search:false },
			{name:'p.name', index:'p.name', width:100, align:'center', sorttype:'text'},
			{name:'totalUnits', index:'totalUnits', width:100, align:'center', sorttype:'int', search:false},
			{name:'totalCost',index:'totalCost', width:100, align:'center ',sorttype:'int', search:false},
			{name:'totalRevenue',index:'totalRevenue', width:120, align:'center',sorttype:'int', search:false},
			{name:'profit',index:'profit', width:100,align:"center",sorttype:'int', search:false,
				cellattr: function (rowid, cellvalue) {
                        var cellStyle = ' style="color:blue;"';
                        if(cellvalue > 0) {
							cellStyle = ' style="color:green;font-weight:bold;"';
                        } else if(cellvalue < 0) {
							cellStyle = ' style="color:red;"';
                        }
                        return cellStyle;
                    }}
		],
		altRows:true,
		altclass:'altRowClass',
		autowidth:false,
		shrinkToFit:true,
		rowNum: 5,
		rowList: [5, 10, 20],
		pager: '#pager',
		jsonReader: {
			root: 'rows',
			page:  'page',
			total: 'total',
			records: 'records'
		}
	});
	//Toolbar with filter button
	grid.jqGrid('filterToolbar');

	/* 
	 * Date picker range in the same widget
	 * See [[ http://www.benknowscode.com/2012/11/selecting-ranges-jquery-ui-datepicker.html ]] for more information 
	 */
	var cur = -1, prv = -1;
	$('#datePicker')
		.datepicker({

			minDate: new Date('{{ dates.minDate }}'),
			maxDate: new Date('{{ dates.maxDate }}'),

			beforeShowDay: function ( date ) {
				return [true, ( (date.getTime() >= Math.min(prv, cur) && date.getTime() <= Math.max(prv, cur)) ? 'date-range-selected' : '')];
			},

			onSelect: function ( dateText, inst ) {
				var d1, d2;

				prv = cur;
				cur = (new Date(inst.selectedYear, inst.selectedMonth, inst.selectedDay)).getTime();
				if ( prv == -1 || prv == cur ) {
					prv = cur;
				}
			},
		});

	//Country select
	$('#country_countries').on('change', function() {
		grid.setGridParam({
			postData: {
				countryId: $(this).val()
			}
		}).trigger('reloadGrid');
	});

	//Reload button on select dates
	$('#reload').on('click', function() {

		var actualDate = '';

		if(cur === -1){

			actualDate = $.datepicker.formatDate('yy/mm/dd', new Date());
			grid.setGridParam({
				postData: {
					date: actualDate,
					from: null,
					to: null
				}
			}).trigger('reloadGrid');

		} else if (cur === prv){

			actualDate = $.datepicker.formatDate('yy/mm/dd', new Date(cur));
			grid.setGridParam({
				postData: {
					date: actualDate,
					from: null,
					to: null
				}
			}).trigger('reloadGrid');

		} else {

			//Fixed selected toDate less than fromDate
			if ( cur < prv) {
				var aux = cur;
				cur = prv;
				prv = aux;
			}

			var dateFrom = $.datepicker.formatDate('yy/mm/dd', new Date(prv));
			var dateTo = $.datepicker.formatDate('yy/mm/dd', new Date(cur));
			grid.setGridParam({
				postData: {
					date: null,
					from: dateFrom,
					to: dateTo
				}
			}).trigger('reloadGrid');
		}
	});
});