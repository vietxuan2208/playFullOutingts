document.addEventListener('DOMContentLoaded', () => {

    const revenueCtx = document.getElementById('revenueChart');
    const salesDistributionCtx = document.getElementById('salesDistributionChart');
    const salesAnalyticsCtx = document.getElementById('salesAnalyticsChart');
    const trafficSourcesCtx = document.getElementById('trafficSourcesChart');
    const deviceBreakdownCtx = document.getElementById('deviceBreakdownChart');

    if(revenueCtx){
        new Chart(revenueCtx.getContext('2d'), {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
                datasets: [{
                    label: 'Revenue',
                    data: [12000, 19000, 15000, 22000, 18000, 24000, 28000],
                    borderColor: '#4361ee',
                    tension: 0.3,
                    fill: true,
                    backgroundColor: context => {
                        const ctx = context.chart.ctx;
                        const gradient = ctx.createLinearGradient(0, 0, 0, 400);
                        gradient.addColorStop(0, 'rgba(67, 97, 238, 0.3)');
                        gradient.addColorStop(1, 'rgba(67, 97, 238, 0.0)');
                        return gradient;
                    }
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            drawBorder: false
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    }

    if (salesDistributionCtx){
        new Chart(salesDistributionCtx.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: ['Electronics', 'Fashion', 'Home & Kitchen', 'Books', 'Others'],
                datasets: [{
                    data: [35, 25, 20, 15, 5],
                    backgroundColor: [
                        '#4361ee',
                        '#3a0ca3',
                        '#7209b7',
                        '#f72585',
                        '#4cc9f0'
                    ],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    }

    if(salesAnalyticsCtx){
        new Chart(salesAnalyticsCtx.getContext('2d'), {
            type: 'bar',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
                datasets: [
                    {
                        label: 'Revenue',
                        data: [12000, 19000, 15000, 22000, 18000, 24000, 28000],
                        backgroundColor: '#4361ee',
                        borderRadius: 5
                    },
                    {
                        label: 'Orders',
                        data: [85, 112, 96, 134, 118, 156, 182],
                        backgroundColor: '#4cc9f0',
                        borderRadius: 5
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }
       
    if(trafficSourcesCtx){
        new Chart(trafficSourcesCtx.getContext('2d'), {
            type: 'pie',
            data: {
                labels: ['Direct', 'Social', 'Email', 'Organic Search', 'Referral'],
                datasets: [{
                    data: [25, 20, 15, 30, 10],
                    backgroundColor: [
                        '#4361ee',
                        '#3a0ca3',
                        '#7209b7',
                        '#f72585',
                        '#4cc9f0'
                    ],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    }

    if(deviceBreakdownCtx){
        new Chart(deviceBreakdownCtx.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: ['Mobile', 'Desktop', 'Tablet'],
                datasets: [{
                    data: [55, 35, 10],
                    backgroundColor: [
                        '#4361ee',
                        '#4cc9f0',
                        '#f72585'
                    ],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    }

    
    


});
