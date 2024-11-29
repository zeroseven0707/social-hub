{{-- <!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Insights Dashboard</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    </head>
    <body>
        <div class="container">
            <h1 class="my-4">Dashboard Insights</h1>
            <div class="row">
                @foreach ($data['data'] as $metric)
                    <div class="col-md-4 mb-4">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title d-flex align-items-center">
                                    <span class="badge bg-primary me-2">{{ $metric['name'] }}</span>
                                    {{ $metric['title'] }}
                                </h5>
                                <p class="card-text text-muted small">{{ $metric['description'] }}</p>

                                <!-- Chart -->
                                <canvas id="chart-{{ $loop->index }}" height="100"></canvas>
                                <script>
                                    document.addEventListener("DOMContentLoaded", function () {
                                        new Chart(document.getElementById("chart-{{ $loop->index }}"), {
                                            type: 'line',
                                            data: {
                                                labels: [
                                                    @foreach($metric['values'] as $value)
                                                        "{{ \Carbon\Carbon::parse($value['end_time'])->format('d M') }}",
                                                    @endforeach
                                                ],
                                                datasets: [{
                                                    label: 'Values',
                                                    data: [
                                                        @foreach($metric['values'] as $value)
                                                            {{ $value['value'] }},
                                                        @endforeach
                                                    ],
                                                    borderColor: 'rgba(75, 192, 192, 1)',
                                                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                                                    borderWidth: 2,
                                                    fill: true,
                                                    tension: 0.4,
                                                }]
                                            },
                                            options: {
                                                responsive: true,
                                                plugins: {
                                                    legend: { display: false }
                                                },
                                                scales: {
                                                    y: { beginAtZero: true }
                                                }
                                            }
                                        });
                                    });
                                </script>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Paging Navigation -->
            <div class="d-flex justify-content-between mt-4">
                @if($data['paging']['previous'] ?? false)
                    <a href="{{ $data['paging']['previous'] }}" class="btn btn-secondary">Previous</a>
                @endif
                @if($data['paging']['next'] ?? false)
                    <a href="{{ $data['paging']['next'] }}" class="btn btn-primary">Next</a>
                @endif
            </div>
        </div>
    </body>
</html>
--}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customizable Insights Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="container">
        <h1 class="my-4">Dashboard Insights</h1>

        <!-- Customization Form -->
        <form action="{{ route('updateMetrics') }}" method="POST" class="mb-4">
            @csrf
            <h3>Choose Metrics to Display</h3>
            <div class="row">
                @foreach($metrics as $metric)
                    <div class="col-md-3">
                        <div class="form-check">
                            <input
                                class="form-check-input"
                                type="checkbox"
                                name="selectedMetrics[]"
                                value="{{ $metric }}"
                                id="metric-{{ $metric }}"
                                {{ in_array($metric, $selectedMetrics) ? 'checked' : '' }}
                            >
                            <label class="form-check-label" for="metric-{{ $metric }}">{{ $metric }}</label>
                        </div>
                    </div>
                @endforeach
            </div>
            <button type="submit" class="btn btn-primary mt-3">Save Selection</button>
        </form>

        <!-- Displaying Selected Metrics -->
        <div class="row">
            @foreach($insightsData as $insight)
                @if(in_array($insight['name'], $selectedMetrics))
                    <div class="col-md-6 mb-4">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title d-flex align-items-center">
                                    <span class="badge bg-primary me-2">{{ $insight['name'] }}</span>
                                    {{ $insight['title'] }}
                                </h5>
                                <p class="card-text text-muted small">{{ $insight['description'] }}</p>

                                <!-- Chart for each selected metric -->
                                <canvas id="chart-{{ $loop->index }}" height="100"></canvas>
                                <script>
                                    document.addEventListener("DOMContentLoaded", function () {
                                        new Chart(document.getElementById("chart-{{ $loop->index }}"), {
                                            type: 'line',
                                            data: {
                                                labels: [
                                                    @foreach($insight['values'] as $value)
                                                        "{{ \Carbon\Carbon::parse($value['end_time'])->format('d M') }}",
                                                    @endforeach
                                                ],
                                                datasets: [{
                                                    label: 'Values',
                                                    data: [
                                                        @foreach($insight['values'] as $value)
                                                            {{ $value['value'] }},
                                                        @endforeach
                                                    ],
                                                    borderColor: 'rgba(75, 192, 192, 1)',
                                                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                                                    borderWidth: 2,
                                                    fill: true,
                                                    tension: 0.4,
                                                }]
                                            },
                                            options: {
                                                responsive: true,
                                                plugins: {
                                                    legend: { display: false }
                                                },
                                                scales: {
                                                    y: { beginAtZero: true }
                                                }
                                            }
                                        });
                                    });
                                </script>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>

        <!-- Pagination Navigation -->
        <div class="d-flex justify-content-between mt-4">
            @if($data['paging']['previous'] ?? false)
                <a href="{{ $data['paging']['previous'] }}" class="btn btn-secondary">Previous</a>
            @endif
            @if($data['paging']['next'] ?? false)
                <a href="{{ $data['paging']['next'] }}" class="btn btn-primary">Next</a>
            @endif
        </div>
    </div>

    <!-- Bootstrap JS for any Bootstrap components -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
