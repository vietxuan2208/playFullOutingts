<div class="card mt-4">
    <div class="card-body">

        <div class="mb-3">
            <button data-range="1W" class="range-btn btn btn-outline-primary btn-sm">1W</button>
            <button data-range="2W" class="range-btn btn btn-outline-primary btn-sm">2W</button>
            <button data-range="1M" class="range-btn btn btn-outline-primary btn-sm">1M</button>
            <button data-range="YTD" class="range-btn btn btn-outline-primary btn-sm">YTD</button>
            <button data-range="ALL" class="range-btn btn btn-primary btn-sm active">ALL</button>
        </div>

        <div id="chart-timeline"></div>
    </div>
</div>


@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<script>
    document.addEventListener("DOMContentLoaded", async function() {

        async function loadData(range = "ALL") {
            let res = await fetch("{{ route('admin.chart.orders') }}?range=" + range);
            let json = await res.json();
            return json.series;
        }

        // === Khởi tạo mặc định: ALL ===
        let initialData = await loadData("ALL");

        let options = {
            series: [{
                name: "Orders",
                data: initialData
            }],
            chart: {
                id: "area-datetime",
                type: "line",
                height: 350,
                zoom: {
                    autoScaleYaxis: true
                }
            },
            stroke: {
                width: 3,
                curve: "smooth"
            },
            markers: {
                size: 4
            },
            xaxis: {
                type: "datetime"
            },
            tooltip: {
                x: {
                    format: "dd MMM yyyy"
                }
            },
            fill: {
                type: "gradient",
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.7,
                    opacityTo: 0.9
                }
            }
        };

        let chart = new ApexCharts(document.querySelector("#chart-timeline"), options);
        chart.render();

        // ==== FILTER BUTTONS ====
        const buttons = document.querySelectorAll(".range-btn");

        buttons.forEach(btn => {
            btn.addEventListener("click", async function() {

                buttons.forEach(b => b.classList.remove("active", "btn-primary"));
                buttons.forEach(b => b.classList.add("btn-outline-primary"));

                btn.classList.remove("btn-outline-primary");
                btn.classList.add("btn-primary", "active");

                let range = btn.dataset.range;

                let newData = await loadData(range);

                chart.updateSeries([{
                    name: "Orders",
                    data: newData
                }]);
            });
        });

    });
</script>

@endpush