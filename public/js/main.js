(function($) {
  // Line Charts
  $('.chartStock').each(function() {
    var chart = $(this);
    chart.addClass('loading');

    $.getJSON(chart.data('chart-url'), function (data) {
      console.log(chart.data('yaxis-title'));
      // Create the chart
      chart.highcharts('StockChart', {
        rangeSelector : {
          selected : 1
        },
        legend: {
          enabled: true
        },
        title : {
          text : chart.data('chart-title')
        },
        yAxis : {
          title: {
            text: chart.data('yaxis-title')
          }
        },
        plotOptions: {
          series: {
            xDateFormat: '%A, %b %e'
          }
        },
        series : data
      });
      chart.removeClass('loading');
    });
  });

  // Guage
  var gaugeOptions = {
    chart: { type: 'solidgauge' },
    title: null,
    pane: {
      size: '160%',
      center: ['50%','90%'],
      startAngle: -90,
      endAngle: 90,
      background: {
        backgroundColor: (Highcharts.theme && Highcharts.theme.background2) || '#EEE',
        innerRadius: '60%',
        outerRadius: '100%',
        shape: 'arc'
      }
    },
    tooltip: {
      enabled: false
    },
    // the value axis
    yAxis: {
      stops: [
        [0.1, '#55BF3B'], // green
        [0.5, '#DDDF0D'], // yellow
        [0.9, '#DF5353'] // red
      ],
      lineWidth: 0,
      minorTickInterval: null,
      gridLineWidth: 0,
      tickWidth: 0,
      labels: {
        enabled: false
      }
    },

    plotOptions: {
      solidgauge: {
        dataLabels: {
          y: -10,
          borderWidth: 0,
          useHTML: true
        }
      }
    }
  };
  $('.chartGuage').each(function() {
    var chart = $(this);
    chart.addClass('loading');

    $.getJSON(chart.data('chart-url'), function (data) {
      chart.highcharts(Highcharts.merge(gaugeOptions, {
        title: {
          text: chart.data('chart-title'),
          margin: -20
        },
        yAxis: {
          min: data.min,
          max: data.max,
          tickPixelInterval: data.max
        },
        credits: { enabled: false },
        series: [{
          name: data.suffix,
          data: [data.value],
          dataLabels: {
            format: '<div style="text-align:center"><span style="font-size:25px;color:' +
            ((Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black') + '">'+data.value_label+'</span><br/>' +
            '<span style="font-size:12px;color:silver">of '+data.max_label+'</span></div>'
          },
          tooltip: {
            valueSuffix: ' '+data.suffix
          }
        }]

      }));
      chart.removeClass('loading');
    });
  });

  $('.chartProgress').each(function() {
    var chart = $(this);
    chart.addClass('loading');

    $.getJSON(chart.data('chart-url'), function (data) {
      var dom = $('<h5>'+chart.data('chart-title')+'</h5><div class="progress round"><span class="meter" style="width:'+data.percentage+'%;"></div>');
      chart.append(dom);

      chart.removeClass('loading');
    });
  });

  $(document).foundation();
}(jQuery));
