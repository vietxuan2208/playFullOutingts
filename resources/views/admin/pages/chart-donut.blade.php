<div class="card mt-4">
    <div class="card-body text-center">

        <!-- Donut centered -->
        <div class="d-flex justify-content-center">
            <div id="donutChart" style="max-width: 520px; width: 100%;"></div>
        </div>

        <!-- 4 nút bạn muốn giữ -->
        <div class="mt-3 d-flex gap-2 justify-content-center">
            <button id="add" class="btn btn-success btn-sm">+ ADD</button>
            <button id="remove" class="btn btn-danger btn-sm">- REMOVE</button>
            <button id="randomize" class="btn btn-primary btn-sm">RANDOMIZE</button>
            <button id="reset" class="btn btn-secondary btn-sm">RESET</button>
        </div>

    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<script>
    document.addEventListener("DOMContentLoaded", async function() {

        // === 1) Gọi API lấy dữ liệu game theo category từ Controller ===
        const res = await fetch("{{ route('admin.chart.gameCategory') }}");
        const data = await res.json();
        // data = { labels: [...], series: [...] }

        // === 2) Config Donut Chart ===
        var donutOptions = {
            series: data.series,
            labels: data.labels,

            chart: {
                width: 500,
                type: 'donut',
            },

            dataLabels: {
                enabled: true
            },

            legend: {
                position: 'right',
                offsetY: 0,
                height: 230,
            },

            responsive: [{
                breakpoint: 480,
                options: {
                    chart: {
                        width: 260
                    },
                    legend: {
                        show: false
                    }
                }
            }],
        };

        var donutChart = new ApexCharts(document.querySelector("#donutChart"), donutOptions);
        donutChart.render();


        // ============================================
        // 3) GIỮ LẠI CÁC NÚT TEST: ADD / REMOVE / RANDOM / RESET
        // ============================================

        function appendData() {
            let arr = donutChart.w.globals.series.slice();
            arr.push(Math.floor(Math.random() * 50) + 1);
            return arr;
        }

        function removeData() {
            let arr = donutChart.w.globals.series.slice();
            arr.pop();
            return arr;
        }

        function randomize() {
            return donutChart.w.globals.series.map(() =>
                Math.floor(Math.random() * 50) + 1
            );
        }

        function reset() {
            return data.series; // quay về dữ liệu thật
        }

        // Gán sự kiện nút
        document.querySelector("#add").addEventListener("click", () => {
            donutChart.updateSeries(appendData());
        });

        document.querySelector("#remove").addEventListener("click", () => {
            donutChart.updateSeries(removeData());
        });

        document.querySelector("#randomize").addEventListener("click", () => {
            donutChart.updateSeries(randomize());
        });

        document.querySelector("#reset").addEventListener("click", () => {
            donutChart.updateSeries(reset());
        });

    });
</script>
@endpush