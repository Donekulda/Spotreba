CanvasJS.addCultureInfo("cz", {
    decimalSeparator: ",",
    digitGroupSeparator: " ",
    days: ["Pondělí", "Úterý", "Středa", "Čtvrtek", "Pátek", "Sobota", "Neděle"],
    shortDays: ["Pon", "Út", "St", "Čt", "Pá", "So", "Ne"],
    months: ['Leden', 'Únor', 'Březen', 'Duben', 'Květen', 'Červen', 'Červenec', 'Srpen', 'Září', 'Říjen', 'Listopad', 'Prosinec'],
    shortMonths: ['Led', 'Ún', 'Břez', 'Dub', 'Květ', 'Červ', 'Čec', 'Srp', 'Zář', 'Říj', 'List', 'Pros']
});

class AreaGraf {
    constructor(id_div, title, faze, od, max) {
        this._id_div = id_div;
        this._title = title;
        this._faze = faze;
        this._od = od;
        this._max = max;

        this._odKdy = 0;
        this._doKdy = 0;

        AreaGraf._dataPoints0 = [];
        AreaGraf._dataPoints1 = [];

        AreaGraf._myObj = new Object();
    }

    addData() {
        //var xmlhttp;
        //var myObj = new Object();
        var DataPointsSP = [];
        var DataPointsFA = [];
        /*
        if (window.XMLHttpRequest) {
            // code for modern browsers
            xmlhttp = new XMLHttpRequest();
        } else {
            // code for old IE browsers
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }

        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                myObj = JSON.parse(this.responseText);

                AreaGraf._odKdy = myObj['odKdy'];
                AreaGraf._doKdy = myObj['doKdy'];

                DataPointsSP = myObj.dataSP;
                DataPointsFA = myObj.dataFA;

                for (var i = 0; i < DataPointsSP.length; i++) {
                    AreaGraf._dataPoints0.push({
                        x: DataPointsSP[i][0],
                        y: DataPointsSP[i][1],
                        color: DataPointsSP[i][2]
                    });
                }

                for (var i = 0; i < DataPointsFA.length; i++) {
                    AreaGraf._dataPoints1.push({
                        x: DataPointsFA[i][0],
                        y: DataPointsFA[i][1],
                        color: DataPointsFA[i][2]
                    });
                }
            }
        };
        xmlhttp.open("GET", "../ajax/SberDatGrafu.php?od=" + this._od + "&max=" + this._max + "&faze=" + this._faze, true);
        xmlhttp.send();
        */
        $.ajax({
            type: "GET",               
            url: "../ajax/SberDatGrafu.php",
            dataType: "json",
            data: {od: this._od, max: this._max, faze: this._faze},
            success: function(data){
                this._odKdy = data['odKdy'];
                this._doKdy = data['doKdy'];
  
                DataPointsSP = data.dataSP;
                DataPointsFA = data.dataFA;
  
                for (var i = 0; i < DataPointsSP.length; i++) {
                    AreaGraf._dataPoints0.push({
                        x: DataPointsSP[i][0],
                        y: DataPointsSP[i][1],
                        color: DataPointsSP[i][2]
                    });
                }
  
                for (var i = 0; i < DataPointsFA.length; i++) {
                    AreaGraf._dataPoints1.push({
                        x: DataPointsFA[i][0],
                        y: DataPointsFA[i][1],
                        color: DataPointsFA[i][2]
                    });
                }     
            }
        });

    }

    renderMultiColoredAreaChart(chart) {
        var multiColoredAreaChart = [];
        for (var i = 0; i < chart.options.data[0].dataPoints.length - 1; i++) {
            multiColoredAreaChart.push({
                type: "area",
                fillOpacity: chart.options.data[0].fillOpacity || 1,
                toolTipContent: null,
                highlightEnabled: false,
                showInLegend: false,
                markerSize: 0,
                color: chart.options.data[0].dataPoints[i].color,
                dataPoints: [{
                    x: chart.options.data[0].dataPoints[i].x,
                    y: chart.options.data[0].dataPoints[i].y
                }, {
                    x: chart.options.data[0].dataPoints[i + 1].x,
                    y: chart.options.data[0].dataPoints[i + 1].y
                }]
            });
        }

        for (var i = 0; i < chart.options.data[1].dataPoints.length - 1; i++) {
            multiColoredAreaChart.push({
                type: "area",
                fillOpacity: chart.options.data[1].fillOpacity || 0.95,
                toolTipContent: null,
                highlightEnabled: true,
                showInLegend: false,
                markerSize: 1,
                color: chart.options.data[1].dataPoints[i].color,
                dataPoints: [{
                    x: chart.options.data[1].dataPoints[i].x,
                    y: chart.options.data[1].dataPoints[i].y
                }, {
                    x: chart.options.data[1].dataPoints[i + 1].x,
                    y: chart.options.data[1].dataPoints[i + 1].y
                }]
            });
        }

        var options = chart.options.data[0];
        options.type = "line";
        options.name = options.name || "DataSeries 1";
        options.color = "black";
        options.xValueFormatString = "h:mm";
        options.legendMarkerType = "square";

        multiColoredAreaChart.push(options);


        var options = chart.options.data[1];
        options.type = "line";
        options.name = options.name || "DataSeries 2";
        options.color = "green";
        options.xValueFormatString = "h:mm";
        options.legendMarkerType = "square";

        multiColoredAreaChart.push(options);

        chart.options.data = multiColoredAreaChart;

        chart.options.toolTip = {
            shared: true,
            content: "<b style='\"'color: {color};'\"'>{x}: </b> {y} Wh",
            borderColor: "black"
        };

    }

    updateChart() {
        this.addData();

        //this.renderMultiColoredAreaChart(this._chart);

        this._chart.render();
        setTimeout(function() { updateChart() }, 60000);
    }

    CreateChart() {
        this.addData();

        this._chart = new CanvasJS.Chart(this._id_div, {
            animationEnabled: true,
            zoomEnabled: true,
            culture: "cz",
            title: {
                text: this._title
            },
            axisX: {
                valueFormatString: "h:mm"
            },
            axisY: {
                title: "Wh",
                maximum: this._max
            },
            legend: {
                verticalAlign: "top",
                horizontalAlign: "right",
                dockInsidePlotArea: true
            },
            toolTip: {
                shared: true
            },
            data: [{
                    name: "Spotřeba",
                    showInLegend: true,
                    legendMarkerType: "square",
                    type: "area",
                    markerSize: 0,
                    fillOpacity: .9,
                    xValueType: "dateTime",
                    dataPoints: AreaGraf._dataPoints0
                },
                {
                    name: "Výroba",
                    showInLegend: true,
                    legendMarkerType: "square",
                    type: "area",
                    color: "rgba(64, 128, 0, 0.8)",
                    markerSize: 0,
                    fillOpacity: .9,
                    xValueType: "dateTime",
                    dataPoints: AreaGraf._dataPoints1
                }
            ]
        });
        console.log(this._chart.options);
        console.log(this._odKdy);      
        console.log("Div id: ", this._id_div);
    }

    renderChart() {
        //this.renderMultiColoredAreaChart(this._chart);

        this._chart.render();

        //this.updateChart();
    }

    LogDataPoint() {
        console.log(AreaGraf._dataPoints0);
        console.log(AreaGraf._dataPoints1);
    }

};