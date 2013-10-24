(function(ng) {
    'use strict';

    ng.module('app.dashboard')
        .service('dailyCost', function(Utils) {

            var body = $('body'),
                bgColor = body.css('background-color'),
                bgText = body.css('color'),
                options = {
                    chart   : {
                        defaultSeriesType : 'line',
                        zoomType          : 'x',
                        backgroundColor   : body.css('background-color')
                    },
                    plotOptions : {
                        line : {
                            events : {
                                legendItemClick : function() {
                                    return false;
                                }
                            }
                        }
                    },
                    credits : {
                        enabled : false
                    },
                    title   : {
                        text  : null,// 'Total Cost of Instances (Monthly View)',
                        style : {
                            color : '#fff'
                        }
                    },
                    xAxis   : {
                        title         : {
                            text   : 'Date',
                            offset : 25,
                            style  : {
                                color         : '#fff',
                                'font-weight' : 'normal'
                            }
                        },
                        type          : 'datetime',
                        tickWidth     : 0,
                        gridLineWidth : 1,
                        labels        : {
                            align     : 'center',
                            x         : -3,
                            y         : 20,
                            formatter : function() {
                                return Highcharts.dateFormat('%m/%d', this.value);
                            }
                        }

                    },
                    yAxis   : {
                        title  : {
                            text  : 'Cost($)',
                            style : {
                                color         : '#fff',
                                'font-weight' : 'normal'
                            }
                        },
                        labels : {
                            formatter : function() {
                                return '$' + this.value;
                            }
                        }
                    },
                    legend  : {
                        layout         : 'vertical',
                        align          : 'right',
                        verticalAlign  : 'top',
                        x              : -10,
                        y              : 100,
                        borderWidth    : 1,
                        itemStyle      : {
                            color : bgText
                        },
                        itemHoverStyle : {
                            color : '#fff'
                        }
                    },
                    tooltip : {
                        valuePrefix : '$',
                        backgroundColor : 'rgba(43, 43, 43, 0.85)',
                        style : {
                            color : '#fff'
                        }
                    },
                    series  : [
                        {
                            name : 'Cost'
                        }
                    ]
                };

            return {
                apply : function(widget, el) {

                    var newOptions = ng.copy(options),
                        data = [];

                    newOptions.chart.renderTo = el.find('.st-widget-wrapper > div')[0];

                    Utils.each(widget.data, function(datum) {

                        data.push([
                            new Date(datum.history_date).getTime(),
                            parseFloat(datum.total)
                        ]);
                    });

                    newOptions.series[0].data = data;
                    new Highcharts.Chart(newOptions);
                }
            };
        });

})(window.angular);