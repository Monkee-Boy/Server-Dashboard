(function($) {
  // Line Charts
  $('.chartStock').each(function() {
    var chart = $(this);
    $.getJSON(chart.data('chart-url'), function (data) {
      console.log(chart.data('yaxis-title'));
      // Create the chart
      chart.highcharts('StockChart', {
        rangeSelector : {
          selected : 1
        },
        title : {
          text : chart.data('chart-title')
        },
        yAxis : {
          title: {
            text: chart.data('yaxis-title')
          }
        },
        series : data
      });
    });
  });
}(jQuery));
