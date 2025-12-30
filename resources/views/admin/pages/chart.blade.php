<div class="card mt-4">
    <div class="card-body">
        <div id="stackedChart"></div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<script>
    document.addEventListener("DOMContentLoaded", async function() {

        // Gọi API lấy dữ liệu thật
        let res = await fetch("{{ route('admin.chart.orderStatus') }}");
        let data = await res.json();

        // Chuẩn bị options cho ApexCharts
        var options = {
            series: [{
                    name: 'Pending',
                    data: data.pending
                },
                {
                    name: 'Shipped',
                    data: data.shipped
                },
                {
                    name: 'Delivered',
                    data: data.delivered
                },
                {
                    name: 'Canceled',
                    data: data.canceled
                }
            ],

            chart: {
                type: 'bar',
                height: 350,
                stacked: true,
                toolbar: {
                    show: true
                },
                zoom: {
                    enabled: true
                }
            },

            plotOptions: {
                bar: {
                    horizontal: false,
                    borderRadius: 8,
                    dataLabels: {
                        total: {
                            enabled: true,
                            style: {
                                fontSize: '12px',
                                fontWeight: 700
                            }
                        }
                    }
                }
            },

            xaxis: {
                categories: data.categories, // ngày từ API
                labels: {
                    rotate: -45
                }
            },

            legend: {
                position: 'right',
                offsetY: 40
            },

            fill: {
                opacity: 1
            }
        };

        var chart = new ApexCharts(document.querySelector("#stackedChart"), options);
        chart.render();

    });
</script>
@endpush