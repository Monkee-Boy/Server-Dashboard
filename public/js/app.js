(function($) {
  // Line Charts
  $('.chartStock').each(function() {
    var chart = $(this);
    chart.addClass('loading');

    $.getJSON(chart.data('chart-url'), function (data) {
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
            xDateFormat: '%A, %b %e',
            stacking: data.stacked
          }
        },
        series : data.chart
      });
      chart.removeClass('loading');
    });
  });

  // Pie Charts
  // Make monochrome colors and set them as default for all pies
  Highcharts.getOptions().plotOptions.pie.colors = (function () {
    var colors = [],
    base = Highcharts.getOptions().colors[0],
    i;

    for (i = 0; i < 10; i += 1) {
      // Start out with a darkened base color (negative brighten), and end
      // up with a much brighter color
      colors.push(Highcharts.Color(base).brighten((i - 3) / 7).get());
    }
    return colors;
  }());
  $('.chartPie').each(function() {
    var chart = $(this);
    chart.addClass('loading');

    $.getJSON(chart.data('chart-url'), function (data) {
      // Create the chart
      chart.highcharts({
        chart: {
          type: 'pie',
          plotBackgroundColor: null,
          plotBorderWidth: null,
          plotShadow: false
        },
        title: {
          text: chart.data('chart-title')
        },
        tooltip: {
          formatter: function() {
            if(chart.data('chart-type')) {
              var size = this.point.y;

              if(size >= 1099511627776) {
                size = Highcharts.numberFormat(size/1099511627776,2)+' TB';
              } else if(size >= 1073741824) {
                size = Highcharts.numberFormat(size/1073741824,2)+' GB';
              } else if(size >= 1048576) {
                size = Highcharts.numberFormat(size/1048576,2)+' MB';
              } else if(size >= 1024) {
                size = Highcharts.numberFormat(size/1024,2)+' KB';
              } else if(size > 0) {
                size = size+' B';
              }

              return this.point.name+': <b>'+size+'</b>';
            }
          }
        },
        plotOptions: {
          pie: {
            allowPointSelect: true,
            cursor: 'pointer',
            dataLabels: {
              enabled: true,
              format: '<b>{point.name}</b>: {point.percentage:.1f} %'
            },
            showInLegend: false
          }
        },
        series: data.chart
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

  $('.chartText').each(function() {
    var chart = $(this);
    chart.addClass('loading');

    $.getJSON(chart.data('chart-url'), function (data) {
      var dom = $('<h5>'+chart.data('chart-title')+'</h5><div class="chart-text">'+data.chart+'</div>');
      chart.append(dom);

      chart.removeClass('loading');
    });
  });

  $('.chartTable').each(function() {
    var chart = $(this);
    chart.parent().addClass('loading');

    $.getJSON(chart.data('chart-url'), function(data) {
      if(data.chart) {
        $.each(data.chart, function(index, row) {
          var row_dom = $('<tr></tr>');
          $.each(row, function(i, data) {
            row_dom.append('<td>'+data+'</td>');
          });
          chart.find('tbody').append(row_dom);
        });
      }

      chart.parent().removeClass('loading');
    });
  });

  $(document).foundation();

  $('.tabs').on('toggled', function (event, tab) {
    tab.find('[class^=chart]').each(function() {
      if($(this).highcharts()) {
        $(this).highcharts().reflow();
      }
    });
  });
}(jQuery));
