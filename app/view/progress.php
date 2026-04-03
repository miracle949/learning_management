<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Progress</title>
    <link rel="stylesheet" href="../css_folder/progress.css">
    <link rel="stylesheet" href="../css_folder/components.css">
    <link rel="stylesheet" href="../bootstrap_folder/css/bootstrap.min.css">
    <link rel="stylesheet" href="../font-awesome-icon/css/all.min.css">
</head>

<body>

    <div class="container-fluid p-0">

        <?php include("../components/offcanvas.php"); ?>
        <?php include("../components/navbar.php"); ?>
        <?php include("../components/sidebar.php"); ?>

        <div class="rightbar">

            <div class="parent-card">
                <div class="box-card">
                    <div class="box-text">
                        <p>Modules Taken</p>

                        <h4>3</h4>
                    </div>
                    <div class="box-icon">
                        <!-- <i class="fa fa-book"></i> -->
                        <i class="fa fa-layer-group"></i>
                    </div>
                </div>

                <div class="box-card">
                    <div class="box-text">
                        <p>Complete Modules</p>

                        <h4>5</h4>
                    </div>
                    <div class="box-icon">
                        <i class="fa fa-check"></i>
                    </div>
                </div>

                <div class="box-card">
                    <div class="box-text">
                        <p>Quizzes</p>

                        <h4>1</h4>
                    </div>
                    <div class="box-icon">
                        <i class="fa fa-file-pen"></i>
                    </div>
                </div>
            </div>

            <div class="bar">
                <div class="parent-bar">
                    <div class="performance">
                        <p class="card-title">Performance by subject</p>
                        <div style="position: relative; width: 100%; height: 160px; margin-top: 1.5rem;">
                            <canvas id="perfChart"></canvas>
                        </div>
                    </div>
                    <div class="completion">
                        <p class="card-title">Module completion status</p>
                        <div style="display: flex; flex-direction: column; align-items: center; margin-top: 1.5rem;">
                            <div style="position: relative; width: 130px; height: 130px; flex-shrink: 0;">
                                <canvas id="pieChart"></canvas>
                            </div>
                            <div class="legend">
                                <div class="legend-item">
                                    <div class="legend-dot"></div>
                                    <span>Completed</span>
                                </div>
                                <div class="legend-item">
                                    <div class="legend-dot"></div>
                                    <span>Not
                                        Completed</span>
                                </div>
                                <div class="legend-item">
                                    <div class="legend-dot"></div>
                                    <span>Remaining</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="chart">
                <div style="display:flex; align-items:center; margin-bottom:12px;">
                    <p class="card-title" style="margin:0;">Quizzes chart</p>
                    <div style="margin-left:auto; display:flex; gap:16px; font-size:12px; color:#888;">
                        <span>Passing (&gt; 60%)</span>
                        <span>Falling (&lt; 60%)</span>
                    </div>
                </div>
                <div style="position: relative; width: 100%; height: 200px;">
                    <canvas id="quizChart"></canvas>
                </div>
            </div>

            <div class="my-modules">
                <h2>Hello</h2>
            </div>

        </div>

    </div>

    <script defer src="../bootstrap_folder/js/bootstrap.bundle.min.js"></script>

    <!-- Chart.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.js"></script>
    <script>
        const subjects = ['ITP', 'ENTREP', 'UCSP', 'CSS'];

        new Chart(document.getElementById('perfChart'), {
            type: 'bar',
            data: {
                labels: subjects,
                datasets: [{ data: [82, 78, 84, 75], backgroundColor: '#1D6348', borderRadius: 4, barPercentage: 0.55 }]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { display: false, beginAtZero: true, max: 100 },
                    x: { grid: { display: false } }
                }
            }
        });

        new Chart(document.getElementById('pieChart'), {
            type: 'doughnut',
            data: {
                labels: ['Completed', 'Not completed', 'Remaining'],
                datasets: [{ data: [45, 20, 35], backgroundColor: ['#1D9E75', '#D85A30', '#E5E5E2'], borderWidth: 0 }]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                cutout: '55%',
                plugins: { legend: { display: false } }
            }
        });

        new Chart(document.getElementById('quizChart'), {
            type: 'bar',
            data: {
                labels: subjects,
                datasets: [{ data: [87, 83, 90, 80], backgroundColor: '#4CAF82', borderRadius: 4, barPercentage: 0.6 }]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, max: 100, ticks: { callback: v => v + '%' } },
                    x: { grid: { display: false } }
                }
            }
        });
    </script>

</body>

</html>