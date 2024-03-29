<?php
    include('include/connection.php');
    
    $category = $sale = '[';
    $sql = "SELECT * FROM category ORDER BY category_name ASC";
    $result = $conn->query($sql);
    while($row = $result->fetch_assoc()){
        $category_id = $row['category_id'];
        $quantity = 0;

        $sql1 = "SELECT * FROM order_detail WHERE category_id='$category_id'";
        $result1 = $conn->query($sql1);
        while($row1 = $result1->fetch_assoc()){
            $order_id = $row1['order_id'];
            if($control == 0){
                $sql2 = "SELECT * FROM orders WHERE order_id='$order_id' AND order_status='5'";
            } else{
                $sql2 = "SELECT * FROM orders WHERE order_id='$order_id' AND order_status='5' AND login_id='$login_id'";
            }
            $result2 = $conn->query($sql2);
            if($result2->num_rows > 0){
                $quantity += $row1['quantity'];
            }
        }

        $category .= '"'.$row['category_name'].'",';
        $sale .= $quantity.',';
    }
    
    $category = rtrim($category, ',').']';
    $sale = rtrim($sale, ',').']';

    $this_month = date('Y-m-d');
    $past_month = date('Y-m-d', strtotime('-11 month'));
    $monthValueCOD = $monthValueONL = $monthName = '[';
    $tot = 0;
    for($i=$past_month;$i<=$this_month;$i=date('Y-m-d', strtotime($i.' +1 month'))){
        $monthName .= '"'.date('M', strtotime($i)).'",';
        $monthStart = date('Y-m-01', strtotime($i));
        $monthEnd = date('Y-m-t', strtotime($i));

        $cod = $onl = 0;
        if($control == 0){
            $sql = "SELECT * FROM orders WHERE booking_date BETWEEN '$monthStart' AND '$monthEnd' AND order_status='5'";
        } else{
            $sql = "SELECT * FROM orders WHERE booking_date BETWEEN '$monthStart' AND '$monthEnd' AND order_status='5' AND login_id='$login_id'";
        }
        $result = $conn->query($sql);
        while($row = $result->fetch_assoc()){
            if($row['payment_type'] == 'Cash On Hand'){
                $cod += $row['total_amount'];
            } else{
                $onl += $row['total_amount'];
            }
            $tot += $row['total_amount'];
        }
        $monthValueCOD .= '"'.$cod.'",';
        $monthValueONL .= '"'.$onl.'",';
    }
    $monthValueCOD = rtrim($monthValueCOD, ',').']';
    $monthValueONL = rtrim($monthValueONL, ',').']';
    $monthName = rtrim($monthName, ',').']';
?>
<script>
    try {
        let dailySalesdays = []
        let dailySalescount = []
        $.ajax({
            type: "POST",
            url: "ajax/map/dailySales.php",
            success: function(data){
            let obj = JSON.parse(data)
            for(let i=0; i<obj.days.length;i++){
                dailySalesdays[i] = obj.days[i]
                dailySalescount[i] = parseInt(obj.count[i])
            }
            }
        });
        var d_2options1 = {
        chart: {
                height: 160,
                type: 'bar',
                stacked: true,
                stackType: '100%',
                toolbar: {
                show: false,
                }
            },
            dataLabels: {
                enabled: false,
            },
            stroke: {
                show: true,
                width: 1,
            },
            colors: ['#e2a03f', '#e0e6ed'],
            responsive: [{
                breakpoint: 480,
                options: {
                    legend: {
                        position: 'bottom',
                        offsetX: -10,
                        offsetY: 0
                    }
                }
            }],
            series: [{
                name: 'Sales',
                data: dailySalescount
            }],
            xaxis: {
                labels: {
                    show: false,
                },
                categories: dailySalesdays,
            },
            yaxis: {
                show: false
            },
            fill: {
                opacity: 1
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    endingShape: 'rounded',
                    columnWidth: '25%',
                }
            },
            legend: {
                show: false,
            },
            grid: {
                show: false,
                xaxis: {
                    lines: {
                        show: false
                    }
                },
                padding: {
                top: 10,
                right: 0,
                bottom: -40,
                left: 0
                }, 
            },
        }

        /*
            =============================
                Total Orders | Options
            =============================
        */
        var d_2options2 = {
        chart: {
            id: 'sparkline1',
            group: 'sparklines',
            type: 'area',
            height: 280,
            sparkline: {
            enabled: true
            },
        },
        stroke: {
            curve: 'smooth',
            width: 2
        },
        fill: {
            opacity: 1,
        },
        series: [{
            name: 'Sales',
            data: [28, 40, 36, 52, 38, 60, 38, 52, 36, 40]
        }],
        labels: ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10'],
        yaxis: {
            min: 0
        },
        grid: {
            padding: {
            top: 125,
            right: 0,
            bottom: 36,
            left: 0
            }, 
        },
        fill: {
            type:"gradient",
            gradient: {
                type: "vertical",
                shadeIntensity: 1,
                inverseColors: !1,
                opacityFrom: .40,
                opacityTo: .05,
                stops: [45, 100]
            }
        },
        tooltip: {
            x: {
            show: false,
            },
            theme: 'dark'
        },
        colors: ['#fff']
        }

        /*
            =================================
                Revenue Monthly | Options
            =================================
        */
        var options1 = {
        chart: {
            fontFamily: 'Nunito, sans-serif',
            height: 365,
            type: 'area',
            zoom: {
                enabled: false
            },
            dropShadow: {
            enabled: true,
            opacity: 0.3,
            blur: 5,
            left: -7,
            top: 22
            },
            toolbar: {
            show: false
            },
            events: {
            mounted: function(ctx, config) {
                const highest1 = ctx.getHighestValueInSeries(0);
                const highest2 = ctx.getHighestValueInSeries(1);

                ctx.addPointAnnotation({
                x: new Date(ctx.w.globals.seriesX[0][ctx.w.globals.series[0].indexOf(highest1)]).getTime(),
                y: highest1,
                label: {
                    style: {
                    cssClass: 'd-none'
                    }
                },
                customSVG: {
                    SVG: '<svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="#1b55e2" stroke="#fff" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="feather feather-circle"><circle cx="12" cy="12" r="10"></circle></svg>',
                    cssClass: undefined,
                    offsetX: -8,
                    offsetY: 5
                }
                })

                ctx.addPointAnnotation({
                x: new Date(ctx.w.globals.seriesX[1][ctx.w.globals.series[1].indexOf(highest2)]).getTime(),
                y: highest2,
                label: {
                    style: {
                    cssClass: 'd-none'
                    }
                },
                customSVG: {
                    SVG: '<svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="#e7515a" stroke="#fff" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="feather feather-circle"><circle cx="12" cy="12" r="10"></circle></svg>',
                    cssClass: undefined,
                    offsetX: -8,
                    offsetY: 5
                }
                })
            },
            }
        },
        colors: ['#1b55e2', '#e7515a'],
        dataLabels: {
            enabled: false
        },
        markers: {
            discrete: [{
            seriesIndex: 0,
            dataPointIndex: 7,
            fillColor: '#000',
            strokeColor: '#000',
            size: 5
        }, {
            seriesIndex: 2,
            dataPointIndex: 11,
            fillColor: '#000',
            strokeColor: '#000',
            size: 4
        }]
        },
        subtitle: {
            text: 'Total Sale',
            align: 'left',
            margin: 0,
            offsetX: -10,
            offsetY: 35,
            floating: false,
            style: {
            fontSize: '14px',
            color:  '#888ea8'
            }
        },
        title: {
            text: '₹<?php echo number_format($tot) ?>',
            align: 'left',
            margin: 0,
            offsetX: -10,
            offsetY: 0,
            floating: false,
            style: {
            fontSize: '25px',
            color:  '#0e1726'
            },
        },
        stroke: {
            show: true,
            curve: 'smooth',
            width: 2,
            lineCap: 'square'
        },
        series: [{
            name: 'Cash On Hand',
            data: <?php echo $monthValueCOD ?>
        }, {
            name: 'Online Payment',
            data: <?php echo $monthValueONL ?>
        }],
        labels: <?php echo $monthName ?>,
        xaxis: {
            axisBorder: {
            show: false
            },
            axisTicks: {
            show: false
            },
            crosshairs: {
            show: true
            },
            labels: {
            offsetX: 0,
            offsetY: 5,
            style: {
                fontSize: '12px',
                fontFamily: 'Nunito, sans-serif',
                cssClass: 'apexcharts-xaxis-title',
            },
            }
        },
        yaxis: {
            labels: {
            formatter: function(value, index) {
                return (value / 1000) + ' K'
            },
            offsetX: -22,
            offsetY: 0,
            style: {
                fontSize: '12px',
                fontFamily: 'Nunito, sans-serif',
                cssClass: 'apexcharts-yaxis-title',
            },
            }
        },
        grid: {
            borderColor: '#e0e6ed',
            strokeDashArray: 5,
            xaxis: {
                lines: {
                    show: true
                }
            },   
            yaxis: {
                lines: {
                    show: false,
                }
            },
            padding: {
            top: 0,
            right: 0,
            bottom: 0,
            left: -10
            }, 
        }, 
        legend: {
            position: 'top',
            horizontalAlign: 'right',
            offsetY: -50,
            fontSize: '16px',
            fontFamily: 'Nunito, sans-serif',
            markers: {
            width: 10,
            height: 10,
            strokeWidth: 0,
            strokeColor: '#fff',
            fillColors: undefined,
            radius: 12,
            onClick: undefined,
            offsetX: 0,
            offsetY: 0
            },    
            itemMargin: {
            horizontal: 0,
            vertical: 20
            }
        },
        tooltip: {
            theme: 'dark',
            marker: {
            show: true,
            },
            x: {
            show: false,
            }
        },
        fill: {
            type:"gradient",
            gradient: {
                type: "vertical",
                shadeIntensity: 1,
                inverseColors: !1,
                opacityFrom: .28,
                opacityTo: .05,
                stops: [45, 100]
            }
        },
        responsive: [{
            breakpoint: 575,
            options: {
            legend: {
                offsetY: -30,
            },
            },
        }]
        }

        /*
            ==================================
                Sales By Category | Options
            ==================================
        */
        var options = {
            chart: {
                type: 'donut',
                width: 380
            },
            colors: ['#5c1ac3', '#e2a03f', '#e7515a', '#e2a03f'],
            dataLabels: {
            enabled: false
            },
            legend: {
                position: 'bottom',
                horizontalAlign: 'center',
                fontSize: '14px',
                markers: {
                width: 10,
                height: 10,
                },
                itemMargin: {
                horizontal: 0,
                vertical: 8
                }
            },
            plotOptions: {
            pie: {
                donut: {
                size: '65%',
                background: 'transparent',
                labels: {
                    show: true,
                    name: {
                    show: true,
                    fontSize: '14px',
                    fontFamily: 'Nunito, sans-serif',
                    color: undefined,
                    offsetY: -10
                    },
                    value: {
                    show: true,
                    fontSize: '26px',
                    fontFamily: 'Nunito, sans-serif',
                    color: '20',
                    offsetY: 16,
                    formatter: function (val) {
                        return val
                    }
                    },
                    total: {
                    show: true,
                    showAlways: true,
                    label: 'Total',
                    color: '#888ea8',
                    formatter: function (w) {
                        return w.globals.seriesTotals.reduce( function(a, b) {
                        return a + b
                        }, 0)
                    }
                    }
                }
                }
            }
            },
            stroke: {
            show: true,
            width: 25,
            },
            series: <?php echo $sale ?>,
            labels: <?php echo $category ?>,
            responsive: [{
                breakpoint: 1599,
                options: {
                    chart: {
                        width: '350px',
                        height: '400px'
                    },
                    legend: {
                        position: 'bottom'
                    }
                },

                breakpoint: 1439,
                options: {
                    chart: {
                        width: '250px',
                        height: '390px'
                    },
                    legend: {
                        position: 'bottom'
                    },
                    plotOptions: {
                    pie: {
                        donut: {
                        size: '65%',
                        }
                    }
                    }
                },
            }]
        }


        /*
            ==============================
            |    @Render Charts Script    |
            ==============================
        */


        /*
            ============================
                Daily Sales | Render
            ============================
        */
        var d_2C_1 = new ApexCharts(document.querySelector("#daily-sales"), d_2options1);
        d_2C_1.render();

        /*
            ============================
                Total Orders | Render
            ============================
        */
        var d_2C_2 = new ApexCharts(document.querySelector("#total-orders"), d_2options2);
        d_2C_2.render();

        /*
            ================================
                Revenue Monthly | Render
            ================================
        */
        var chart1 = new ApexCharts(
            document.querySelector("#revenueMonthly"),
            options1
        );

        chart1.render();

        /*
            =================================
                Sales By Category | Render
            =================================
        */
        var chart = new ApexCharts(
            document.querySelector("#chart-2"),
            options
        );

        chart.render();

        /*
            =============================================
                Perfect Scrollbar | Recent Activities
            =============================================
        */
        const ps = new PerfectScrollbar(document.querySelector('.mt-container'));
    } catch(e) {
        // console.log(e);
    }
</script>