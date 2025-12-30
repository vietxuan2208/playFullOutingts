@extends('admin.dashboard') {{-- layout cha giữ nguyên --}}

@section('content')
<style>
    /* KPI CARDS */
    .kpi-row .kpi-card {
        display: flex;
        align-items: center;
        gap: 15px;
        background: #ffffff;
        border-radius: 12px;
        padding: 22px 18px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        transition: all 0.25s ease;
        cursor: pointer;
        border: 1px solid #f1f1f1;
    }

    .kpi-row .kpi-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 12px 25px rgba(0, 0, 0, 0.12);
    }

    .kpi-icon {
        width: 55px;
        height: 55px;
        border-radius: 50%;
        color: #fff;
        font-size: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
        flex-shrink: 0;
    }

    /* Custom gradients for icons */
    .kpi-icon.bg-primary {
        background: linear-gradient(135deg, #4e73df, #224abe);
    }

    .kpi-icon.bg-success {
        background: linear-gradient(135deg, #1cc88a, #17a673);
    }

    .kpi-icon.bg-warning {
        background: linear-gradient(135deg, #f6c23e, #dda20a);
    }

    .kpi-icon.bg-danger {
        background: linear-gradient(135deg, #e74a3b, #c0392b);
    }

    .kpi-content h6 {
        margin: 0;
        font-size: 14px;
        color: #6c757d;
        text-transform: uppercase;
        letter-spacing: .5px;
        font-weight: 600;
    }

    .kpi-content h3 {
        margin: 0;
        font-weight: 800;
        font-size: 28px;
        color: #333;
    }

    .shadow-sm {
        display: flex;
        background: #ffffff;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        transition: all 0.25s ease;
        cursor: pointer;
        border: 1px solid #f1f1f1;
    }

    .shadow-sm:hover {
        transform: translateY(-6px);
        box-shadow: 0 12px 25px rgba(0, 0, 0, 0.12);
    }
</style>
<div class="container-fluid py-3 pt-5">
    <div class="row mb-4 kpi-row">

        <div class="col-md-3">
            <div class="kpi-card">
                <div class="kpi-icon bg-primary"><i class="bi bi-people"></i></div>
                <div class="kpi-content">
                    <h6>Total Users</h6>
                    <h3>{{ $totalUsers }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="kpi-card">
                <div class="kpi-icon bg-success"><i class="bi bi-controller"></i></div>
                <div class="kpi-content">
                    <h6>Total Game</h6>
                    <h3>{{ $totalGames }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="kpi-card">
                <div class="kpi-icon bg-warning"><i class="bi bi-bag-check"></i></div>
                <div class="kpi-content">
                    <h6>Total Product</h6>
                    <h3>{{ $totalProducts }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="kpi-card">
                <div class="kpi-icon bg-danger"><i class="bi bi-grid-1x2"></i></div>
                <div class="kpi-content">
                    <h6>Total Category</h6>
                    <h3>{{ $totalCategories }}</h3>
                </div>
            </div>
        </div>

    </div>

    {{-- ============================ --}}
    {{-- TIMELINE CHART      --}}
    {{-- ============================ --}}
    <div class="card mb-4 shadow-sm">
        <div class="card-header">
            <h5 class="card-title mb-0">Sales Timeline</h5>
        </div>
        <div class="card-body">
            @include('admin.pages.chart-timeline')
        </div>
    </div>


    {{-- ============================ --}}
    {{-- DONUT CHART         --}}
    {{-- ============================ --}}
    <div class="card mb-4 shadow-sm">
        <div class="card-header">
            <h5 class="card-title mb-0">Donut Chart</h5>
        </div>
        <div class="card-body">
            @include('admin.pages.chart-donut')
        </div>
    </div>


    {{-- ============================ --}}
    {{-- STACKED COLUMN CHART   --}}
    {{-- ============================ --}}
    <div class="card mb-4 shadow-sm">
        <div class="card-header">
            <h5 class="card-title mb-0">Stacked Columns</h5>
        </div>
        <div class="card-body">
            @include('admin.pages.chart')
        </div>
    </div>

</div>

@endsection