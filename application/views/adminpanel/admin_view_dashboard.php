<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Queuing System Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/chart.js/3.9.1/chart.min.js"></script>
    <style>
        .dashboard-card {
            transition: all 0.3s ease;
        }
        .dashboard-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
        }
        .sector-icon {
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }
        .status-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: bold;
        }
        .current-time {
            text-shadow: 0 1px 2px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">Queuing System Dashboard</h1>
                    <p class="text-gray-600">Real-time monitoring of queue status across departments</p>
                </div>
                <div class="text-right">
                    <div id="currentDate" class="text-xl font-semibold text-gray-700"></div>
                    <div id="currentTime" class="text-2xl font-bold text-blue-600 current-time"></div>
                </div>
            </div>
        </div>
        
        <!-- Overview Stats -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow-md p-6 dashboard-card">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-gray-500 text-sm font-medium">Total Clients</h3>
                        <div class="mt-1">
                            <span class="text-3xl font-bold text-gray-900" id="totalClients">0</span>
                        </div>
                    </div>
                    <div class="bg-blue-100 rounded-full p-3">
                        <i class="fas fa-users text-blue-500"></i>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-md p-6 dashboard-card">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-gray-500 text-sm font-medium">Active Sectors</h3>
                        <div class="mt-1">
                            <span class="text-3xl font-bold text-gray-900">7</span>
                        </div>
                    </div>
                    <div class="bg-green-100 rounded-full p-3">
                        <i class="fas fa-building text-green-500"></i>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-md p-6 dashboard-card">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-gray-500 text-sm font-medium">Avg. Wait Time</h3>
                        <div class="mt-1">
                            <span class="text-3xl font-bold text-gray-900">15</span>
                            <span class="text-gray-500 text-sm">min</span>
                        </div>
                    </div>
                    <div class="bg-yellow-100 rounded-full p-3">
                        <i class="fas fa-clock text-yellow-500"></i>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-md p-6 dashboard-card">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-gray-500 text-sm font-medium">Completed Today</h3>
                        <div class="mt-1">
                            <span class="text-3xl font-bold text-gray-900" id="completedToday">0</span>
                        </div>
                    </div>
                    <div class="bg-purple-100 rounded-full p-3">
                        <i class="fas fa-check-circle text-purple-500"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Sector Status Cards -->
        <h2 class="text-2xl font-bold text-gray-800 mb-4">Sector Status</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-8">
            <!-- Land Tax -->
            <div class="bg-white rounded-lg shadow-md p-6 dashboard-card relative">
                <span class="status-badge bg-green-100 text-green-800">Active</span>
                <div class="text-center mb-4">
                    <div class="sector-icon text-blue-500">
                        <i class="fas fa-file-invoice-dollar"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800">Land Tax</h3>
                </div>
                <div class="flex justify-between items-center">
                    <div class="text-center">
                        <p class="text-sm text-gray-500">In Queue</p>
                        <p class="text-2xl font-bold text-blue-600" id="landtaxCount">0</p>
                    </div>
                    <div class="text-center">
                        <p class="text-sm text-gray-500">Processing</p>
                        <p class="text-2xl font-bold text-yellow-500" id="landtaxProcessing">0</p>
                    </div>
                    <div class="text-center">
                        <p class="text-sm text-gray-500">Completed</p>
                        <p class="text-2xl font-bold text-green-500" id="landtaxCompleted">0</p>
                    </div>
                </div>
            </div>
            
            <!-- Backroom -->
            <div class="bg-white rounded-lg shadow-md p-6 dashboard-card relative">
                <span class="status-badge bg-green-100 text-green-800">Active</span>
                <div class="text-center mb-4">
                    <div class="sector-icon text-indigo-500">
                        <i class="fas fa-door-closed"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800">Backroom</h3>
                </div>
                <div class="flex justify-between items-center">
                    <div class="text-center">
                        <p class="text-sm text-gray-500">In Queue</p>
                        <p class="text-2xl font-bold text-blue-600" id="backroomCount">0</p>
                    </div>
                    <div class="text-center">
                        <p class="text-sm text-gray-500">Processing</p>
                        <p class="text-2xl font-bold text-yellow-500" id="backroomProcessing">0</p>
                    </div>
                    <div class="text-center">
                        <p class="text-sm text-gray-500">Completed</p>
                        <p class="text-2xl font-bold text-green-500" id="backroomCompleted">0</p>
                    </div>
                </div>
            </div>
            
            <!-- Examiners -->
            <div class="bg-white rounded-lg shadow-md p-6 dashboard-card relative">
                <span class="status-badge bg-green-100 text-green-800">Active</span>
                <div class="text-center mb-4">
                    <div class="sector-icon text-red-500">
                        <i class="fas fa-search"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800">Examiners</h3>
                </div>
                <div class="flex justify-between items-center">
                    <div class="text-center">
                        <p class="text-sm text-gray-500">In Queue</p>
                        <p class="text-2xl font-bold text-blue-600" id="examinersCount">0</p>
                    </div>
                    <div class="text-center">
                        <p class="text-sm text-gray-500">Processing</p>
                        <p class="text-2xl font-bold text-yellow-500" id="examinersProcessing">0</p>
                    </div>
                    <div class="text-center">
                        <p class="text-sm text-gray-500">Completed</p>
                        <p class="text-2xl font-bold text-green-500" id="examinersCompleted">0</p>
                    </div>
                </div>
            </div>
            
            <!-- Business Tax -->
            <div class="bg-white rounded-lg shadow-md p-6 dashboard-card relative">
                <span class="status-badge bg-green-100 text-green-800">Active</span>
                <div class="text-center mb-4">
                    <div class="sector-icon text-green-500">
                        <i class="fas fa-store"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800">Business Tax</h3>
                </div>
                <div class="flex justify-between items-center">
                    <div class="text-center">
                        <p class="text-sm text-gray-500">In Queue</p>
                        <p class="text-2xl font-bold text-blue-600" id="businesstaxCount">0</p>
                    </div>
                    <div class="text-center">
                        <p class="text-sm text-gray-500">Processing</p>
                        <p class="text-2xl font-bold text-yellow-500" id="businesstaxProcessing">0</p>
                    </div>
                    <div class="text-center">
                        <p class="text-sm text-gray-500">Completed</p>
                        <p class="text-2xl font-bold text-green-500" id="businesstaxCompleted">0</p>
                    </div>
                </div>
            </div>
            
            <!-- Payment -->
            <div class="bg-white rounded-lg shadow-md p-6 dashboard-card relative">
                <span class="status-badge bg-green-100 text-green-800">Active</span>
                <div class="text-center mb-4">
                    <div class="sector-icon text-yellow-500">
                        <i class="fas fa-money-bill-wave"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800">Payment</h3>
                </div>
                <div class="flex justify-between items-center">
                    <div class="text-center">
                        <p class="text-sm text-gray-500">In Queue</p>
                        <p class="text-2xl font-bold text-blue-600" id="paymentCount">0</p>
                    </div>
                    <div class="text-center">
                        <p class="text-sm text-gray-500">Processing</p>
                        <p class="text-2xl font-bold text-yellow-500" id="paymentProcessing">0</p>
                    </div>
                    <div class="text-center">
                        <p class="text-sm text-gray-500">Completed</p>
                        <p class="text-2xl font-bold text-green-500" id="paymentCompleted">0</p>
                    </div>
                </div>
            </div>
            
            <!-- Fire Protection -->
            <div class="bg-white rounded-lg shadow-md p-6 dashboard-card relative">
                <span class="status-badge bg-green-100 text-green-800">Active</span>
                <div class="text-center mb-4">
                    <div class="sector-icon text-orange-500">
                        <i class="fas fa-fire-extinguisher"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800">Fire Protection</h3>
                </div>
                <div class="flex justify-between items-center">
                    <div class="text-center">
                        <p class="text-sm text-gray-500">In Queue</p>
                        <p class="text-2xl font-bold text-blue-600" id="fireprotectionCount">0</p>
                    </div>
                    <div class="text-center">
                        <p class="text-sm text-gray-500">Processing</p>
                        <p class="text-2xl font-bold text-yellow-500" id="fireprotectionProcessing">0</p>
                    </div>
                    <div class="text-center">
                        <p class="text-sm text-gray-500">Completed</p>
                        <p class="text-2xl font-bold text-green-500" id="fireprotectionCompleted">0</p>
                    </div>
                </div>
            </div>
            
            <!-- Releasing -->
            <div class="bg-white rounded-lg shadow-md p-6 dashboard-card relative">
                <span class="status-badge bg-green-100 text-green-800">Active</span>
                <div class="text-center mb-4">
                    <div class="sector-icon text-purple-500">
                        <i class="fas fa-clipboard-check"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800">Releasing</h3>
                </div>
                <div class="flex justify-between items-center">
                    <div class="text-center">
                        <p class="text-sm text-gray-500">In Queue</p>
                        <p class="text-2xl font-bold text-blue-600" id="releasingCount">0</p>
                    </div>
                    <div class="text-center">
                        <p class="text-sm text-gray-500">Processing</p>
                        <p class="text-2xl font-bold text-yellow-500" id="releasingProcessing">0</p>
                    </div>
                    <div class="text-center">
                        <p class="text-sm text-gray-500">Completed</p>
                        <p class="text-2xl font-bold text-green-500" id="releasingCompleted">0</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Charts Row -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Queue Volume Chart -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-xl font-semibold text-gray-800 mb-4">Queue Volume by Sector</h3>
                <div class="h-64">
                    <canvas id="queueVolumeChart"></canvas>
                </div>
            </div>
            
            <!-- Service Time Chart -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-xl font-semibold text-gray-800 mb-4">Average Service Time (mins)</h3>
                <div class="h-64">
                    <canvas id="serviceTimeChart"></canvas>
                </div>
            </div>
        </div>
        
        <!-- Recent Activity Table -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-semibold text-gray-800">Recent Activity</h3>
                <button class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md text-sm font-medium">View All</button>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Queue #</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sector</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200" id="activityTable">
                        <!-- Activity rows will be populated by JavaScript -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <script>
        // Initialize date and time
        function updateDateTime() {
            const now = new Date();
            document.getElementById('currentDate').textContent = now.toLocaleDateString('en-US', { 
                weekday: 'long', 
                year: 'numeric', 
                month: 'long', 
                day: 'numeric' 
            });
            document.getElementById('currentTime').textContent = now.toLocaleTimeString('en-US', { 
                hour: '2-digit', 
                minute: '2-digit', 
                second: '2-digit' 
            });
        }
        updateDateTime();
        setInterval(updateDateTime, 1000);
        
        // Sample data for initial load
        const sampleSectorData = {
            'landtax': { count: 15, processing: 3, completed: 8 },
            'backroom': { count: 7, processing: 2, completed: 5 },
            'examiners': { count: 10, processing: 4, completed: 6 },
            'businesstax': { count: 12, processing: 2, completed: 4 },
            'payment': { count: 8, processing: 1, completed: 7 },
            'fireprotection': { count: 5, processing: 1, completed: 3 },
            'releasing': { count: 9, processing: 2, completed: 6 }
        };
        
        // Update sector counters
        function updateSectorCounters(data) {
            let totalClients = 0;
            let totalCompleted = 0;
            
            for (const [sector, stats] of Object.entries(data)) {
                document.getElementById(`${sector}Count`).textContent = stats.count;
                document.getElementById(`${sector}Processing`).textContent = stats.processing;
                document.getElementById(`${sector}Completed`).textContent = stats.completed;
                
                totalClients += stats.count + stats.processing;
                totalCompleted += stats.completed;
            }
            
            document.getElementById('totalClients').textContent = totalClients;
            document.getElementById('completedToday').textContent = totalCompleted;
        }
        
        // Initialize charts
        function initCharts(data) {
            // Queue Volume Chart
            const queueCtx = document.getElementById('queueVolumeChart').getContext('2d');
            const queueChart = new Chart(queueCtx, {
                type: 'bar',
                data: {
                    labels: Object.keys(data).map(key => key.charAt(0).toUpperCase() + key.slice(1)),
                    datasets: [{
                        label: 'In Queue',
                        data: Object.values(data).map(stats => stats.count),
                        backgroundColor: '#3B82F6',
                        borderColor: '#2563EB',
                        borderWidth: 1
                    }, {
                        label: 'Processing',
                        data: Object.values(data).map(stats => stats.processing),
                        backgroundColor: '#FBBF24',
                        borderColor: '#D97706',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
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
            
            // Service Time Chart
            const serviceCtx = document.getElementById('serviceTimeChart').getContext('2d');
            const serviceChart = new Chart(serviceCtx, {
                type: 'horizontalBar',
                data: {
                    labels: Object.keys(data).map(key => key.charAt(0).toUpperCase() + key.slice(1)),
                    datasets: [{
                        label: 'Average Minutes',
                        data: [18, 12, 22, 15, 8, 10, 5],
                        backgroundColor: [
                            '#3B82F6', '#8B5CF6', '#EC4899', 
                            '#10B981', '#F59E0B', '#EF4444', '#6366F1'
                        ],
                        borderWidth: 0
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: {
                            beginAtZero: true,
                            grid: {
                                drawBorder: false
                            }
                        },
                        y: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        }
        
        // Populate recent activity table
        function populateActivityTable() {
            const activityData = [
                { queue: '102', name: 'John Smith', sector: 'Land Tax', status: 'Completed', time: '10:15 AM' },
                { queue: '103', name: 'Maria Garcia', sector: 'Backroom', status: 'Processing', time: '10:18 AM' },
                { queue: '104', name: 'Robert Jones', sector: 'Examiners', status: 'In Queue', time: '10:20 AM' },
                { queue: '105', name: 'Sarah Williams', sector: 'Business Tax', status: 'Completed', time: '10:25 AM' },
                { queue: '106', name: 'David Brown', sector: 'Payment', status: 'Processing', time: '10:30 AM' }
            ];
            
            const activityTable = document.getElementById('activityTable');
            activityTable.innerHTML = '';
            
            activityData.forEach(activity => {
                let statusClass = '';
                switch(activity.status) {
                    case 'Completed': statusClass = 'bg-green-100 text-green-800'; break;
                    case 'Processing': statusClass = 'bg-yellow-100 text-yellow-800'; break;
                    case 'In Queue': statusClass = 'bg-blue-100 text-blue-800'; break;
                }
                
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${activity.queue}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${activity.name}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${activity.sector}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${statusClass}">
                            ${activity.status}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${activity.time}</td>
                `;
                activityTable.appendChild(row);
            });
        }
        
        // Initialize the dashboard
        function initDashboard() {
            updateSectorCounters(sampleSectorData);
            initCharts(sampleSectorData);
            populateActivityTable();
            
            // In a real application, you would set up WebSocket or polling to update the data
            // For this demo, we'll just update some numbers randomly
            setInterval(() => {
                // Randomly change some numbers to simulate live data
                Object.keys(sampleSectorData).forEach(sector => {
                    if (Math.random() > 0.7) {
                        // 30% chance to change some values
                        const change = Math.floor(Math.random() * 3) - 1; // -1, 0, or 1
                        sampleSectorData[sector].count = Math.max(0, sampleSectorData[sector].count + change);
                        
                        if (Math.random() > 0.5) {
                            const processChange = Math.floor(Math.random() * 2); // 0 or 1
                            sampleSectorData[sector].processing = Math.max(0, sampleSectorData[sector].processing + (Math.random() > 0.5 ? processChange : -processChange));
                        }
                        
                        if (Math.random() > 0.7) {
                            sampleSectorData[sector].completed += 1;
                        }
                    }
                });
                
                updateSectorCounters(sampleSectorData);
            }, 5000);
        }
        
        // Initialize the dashboard when the page loads
        window.addEventListener('DOMContentLoaded', initDashboard);
    </script>
</body>
</html>