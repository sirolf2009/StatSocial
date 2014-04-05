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

$(function () {
	$.ajax({
		type: "POST",
		url: "/roaddata/getDataTypesChart"
	})
		.done(function (data) {
			$('#container_pie').highcharts({
				chart: {
					plotBackgroundColor: null,
					plotBorderWidth: null,
					plotShadow: false
				},
				title: {
					text: 'Voorkomende file oorzaken '
				},
				tooltip: {
					pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
				},
				plotOptions: {
					pie: {
						allowPointSelect: true,
						cursor: 'pointer',
						dataLabels: {
							enabled: true,
							color: '#000000',
							connectorColor: '#000000',
							format: '<b>{point.name}</b>: {point.percentage:.1f} %'
						}
					}
				},
				series: [
					{
						type: 'pie',
						name: 'File oorzaken',
						data:
							data

					}
				]
			});
		});
});

$(function () {
    $.ajax({
        type: "POST",
        url: "/roaddata/getPostData"
    })
        .done(function (data) {
            $('#posts_container').highcharts({
                chart: {
                    type: 'column'
                },
                title: {
                    text: 'Aantal berichten per weg (top 20)'
                },
                xAxis: {
                    categories: data.roads
                },
                yAxis: {
                    title: {
                        text: 'Aantal'
                    }
                },
                series: [{
                        name: 'Totaal',
                        data: data.count
                    }]
            });
        });
});

$(function () {
    $.ajax({
        type: "POST",
        url: "/roaddata/getPostData/medium"
    })
        .done(function (data) {
            $('#medium_container').highcharts({
                chart: {
                    type: 'column'
                },
                title: {
                    text: 'Aantal berichten per weg (top 20)'
                },
                xAxis: {
                    categories: data.roads
                },
                yAxis: {
                    title: {
                        text: 'Aantal'
                    }
                },
                series: [{
                        name: 'Twitter',
                        data: data.twitter
                    },
                    {
                        name: 'Facebook',
                        data: data.facebook
                    }]
            });
        });
});