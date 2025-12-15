// Advanced Reporting Charts Helper
(function() {
    'use strict';

    // Chart.js configuration
    const chartDefaults = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'top',
            },
            tooltip: {
                mode: 'index',
                intersect: false,
            }
        },
        scales: {
            x: {
                display: true,
                title: {
                    display: true,
                    text: 'Thời gian'
                }
            },
            y: {
                display: true,
                beginAtZero: true
            }
        }
    };

    // Create line chart
    window.createLineChart = function(canvasId, data, options = {}) {
        const ctx = document.getElementById(canvasId);
        if (!ctx) return null;

        const config = {
            type: 'line',
            data: data,
            options: { ...chartDefaults, ...options }
        };

        return new Chart(ctx, config);
    };

    // Create bar chart
    window.createBarChart = function(canvasId, data, options = {}) {
        const ctx = document.getElementById(canvasId);
        if (!ctx) return null;

        const config = {
            type: 'bar',
            data: data,
            options: { ...chartDefaults, ...options }
        };

        return new Chart(ctx, config);
    };

    // Create doughnut chart
    window.createDoughnutChart = function(canvasId, data, options = {}) {
        const ctx = document.getElementById(canvasId);
        if (!ctx) return null;

        const config = {
            type: 'doughnut',
            data: data,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                    }
                }
            }
        };

        return new Chart(ctx, config);
    };

    // Create pie chart
    window.createPieChart = function(canvasId, data, options = {}) {
        const ctx = document.getElementById(canvasId);
        if (!ctx) return null;

        const config = {
            type: 'pie',
            data: data,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                    }
                }
            }
        };

        return new Chart(ctx, config);
    };

    // Load borrowing trends chart
    window.loadBorrowingTrendsChart = function(canvasId, days = 30) {
        fetch(`/admin/reports/advanced/borrowing-trends-chart?days=${days}`)
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    createLineChart(canvasId, result.data, {
                        scales: {
                            x: {
                                display: true,
                                title: {
                                    display: true,
                                    text: 'Ngày'
                                }
                            },
                            y: {
                                display: true,
                                beginAtZero: true,
                                title: {
                                    display: true,
                                    text: 'Số lượng'
                                }
                            }
                        }
                    });
                }
            })
            .catch(error => {
                console.error('Error loading borrowing trends chart:', error);
            });
    };

    // Load fine trends chart
    window.loadFineTrendsChart = function(canvasId, days = 30) {
        fetch(`/admin/reports/advanced/fine-trends-chart?days=${days}`)
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    createLineChart(canvasId, result.data, {
                        scales: {
                            x: {
                                display: true,
                                title: {
                                    display: true,
                                    text: 'Ngày'
                                }
                            },
                            y: {
                                display: true,
                                beginAtZero: true,
                                title: {
                                    display: true,
                                    text: 'Số tiền (VNĐ)'
                                },
                                ticks: {
                                    callback: function(value) {
                                        return new Intl.NumberFormat('vi-VN').format(value) + ' VNĐ';
                                    }
                                }
                            }
                        }
                    });
                }
            })
            .catch(error => {
                console.error('Error loading fine trends chart:', error);
            });
    };

    // Load books by category chart
    window.loadBooksByCategoryChart = function(canvasId) {
        fetch('/admin/reports/advanced/books-by-category')
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    const data = {
                        labels: result.data.map(item => item.name),
                        datasets: [{
                            data: result.data.map(item => item.count),
                            backgroundColor: [
                                '#FF6384',
                                '#36A2EB',
                                '#FFCE56',
                                '#4BC0C0',
                                '#9966FF',
                                '#FF9F40',
                                '#FF6384',
                                '#C9CBCF'
                            ]
                        }]
                    };
                    createDoughnutChart(canvasId, data);
                }
            })
            .catch(error => {
                console.error('Error loading books by category chart:', error);
            });
    };

    // Load real-time statistics
    window.loadRealTimeStats = function() {
        fetch('/admin/reports/advanced/real-time-stats')
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    updateStatsCards(result.data);
                }
            })
            .catch(error => {
                console.error('Error loading real-time stats:', error);
            });
    };

    // Update statistics cards
    function updateStatsCards(stats) {
        const cards = {
            'today-borrows': stats.today_borrows,
            'today-returns': stats.today_returns,
            'active-borrows': stats.active_borrows,
            'overdue-borrows': stats.overdue_borrows,
            'pending-reservations': stats.pending_reservations,
            'pending-fines': stats.pending_fines
        };

        Object.keys(cards).forEach(key => {
            const element = document.getElementById(key);
            if (element) {
                if (key === 'pending-fines') {
                    element.textContent = new Intl.NumberFormat('vi-VN').format(cards[key]) + ' VNĐ';
                } else {
                    element.textContent = cards[key];
                }
            }
        });
    }

    // Auto-refresh real-time stats every 30 seconds
    setInterval(loadRealTimeStats, 30000);

    // Export report function
    window.exportReport = function(type, filename = null) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '/admin/reports/advanced/export';
        
        const typeInput = document.createElement('input');
        typeInput.type = 'hidden';
        typeInput.name = 'type';
        typeInput.value = type;
        
        if (filename) {
            const filenameInput = document.createElement('input');
            filenameInput.type = 'hidden';
            filenameInput.name = 'filename';
            filenameInput.value = filename;
            form.appendChild(filenameInput);
        }
        
        form.appendChild(typeInput);
        document.body.appendChild(form);
        form.submit();
        document.body.removeChild(form);
    };

    // Format number with Vietnamese locale
    window.formatNumber = function(number) {
        return new Intl.NumberFormat('vi-VN').format(number);
    };

    // Format currency
    window.formatCurrency = function(amount) {
        return new Intl.NumberFormat('vi-VN', {
            style: 'currency',
            currency: 'VND'
        }).format(amount);
    };

    // Format date
    window.formatDate = function(dateString) {
        return new Date(dateString).toLocaleDateString('vi-VN');
    };

    // Format datetime
    window.formatDateTime = function(dateString) {
        return new Date(dateString).toLocaleString('vi-VN');
    };

})();























