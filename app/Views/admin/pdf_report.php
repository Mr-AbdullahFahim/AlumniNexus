<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Analytics Report</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .chart-container {
            margin-bottom: 40px;
            page-break-inside: avoid;
        }
        .chart-title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .chart-image {
            width: 100%;
            max-width: 600px;
            height: auto;
            border: 1px solid #ccc;
        }
        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 10px;
            color: #777;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Alumni Analytics Report</h1>
        <p>Generated on <?= date('Y-m-d H:i:s') ?></p>
    </div>

    <?php if (isset($charts) && is_array($charts)): ?>
        <?php foreach ($charts as $chart): ?>
            <div class="chart-container">
                <div class="chart-title"><?= htmlspecialchars($chart['title']) ?></div>
                <!-- $chart['imageBase64'] should contain the data URL e.g. data:image/png;base64,... -->
                <img class="chart-image" src="<?= $chart['imageBase64'] ?>" alt="<?= htmlspecialchars($chart['title']) ?>">
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

    <div class="footer">
        AlumniNexus Analytics Dashboard
    </div>
</body>
</html>
