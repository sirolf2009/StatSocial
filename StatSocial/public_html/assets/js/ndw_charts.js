$(function () {


	$.ajax({
		type: "POST",
		url: "/roaddata/getData"
	})
		.done(function (data) {
			$('#container').highcharts({
				chart: {
					type: 'bar'
				},
				title: {
					text: 'Aantal gebeurtenissen per weg (top 20)'
				},
				xAxis: {
					categories: data.road
				},
				yAxis: {
					title: {
						text: 'Aantal'
					}
				},
				series: [
					{
						name: 'Totaal',
						data: data.count
					},
					{
						name: 'Ongelukken',
						data: data.accident
					},
					{
						name: 'Anders',
						data: data.other
					},
					{
						name: 'Kijkers File',
						data: data.rubberNecking
					},
					{
						name: 'Eerder ongeluk',
						data: data.earlierAccident
					}
				]
			});
		});


});