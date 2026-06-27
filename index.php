<?php
/**
 * Calculates electricity rates per hour and per day based on inputs.
 * * Formulas used:
 * Power (Wh) = Voltage (V) * Current (A)
 * Energy (kWh) = (Power * Hour) / 1000
 * Total = Energy (kWh) * (Current Rate / 100)
 *
 * @param float $voltage
 * @param float $current
 * @param float $rate 
 * @return array
 */
function calculateElectricityRates($voltage, $current, $rate) {
    $powerWh = $voltage * $current;
    $powerkW = $powerWh / 1000;
    $rateRM = $rate / 100;

    $hourlyData = [];
    for ($hour = 1; $hour <= 24; $hour++) {
        $energyKwh = $powerkW * $hour;
        $totalRM = $energyKwh * $rateRM;
        
        $hourlyData[] = [
            'hour' => $hour,
            'energy' => round($energyKwh, 5),
            'total' => round($totalRM, 2)
        ];
    }

    return [
        'power_kw' => $powerkW,
        'rate_rm' => $rateRM,
        'hourly_data' => $hourlyData
    ];
}

$voltage = isset($_POST['voltage']) ? floatval($_POST['voltage']) : 19;
$current = isset($_POST['current']) ? floatval($_POST['current']) : 3.24;
$rate = isset($_POST['rate']) ? floatval($_POST['rate']) : 21.80; 

$results = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $results = calculateElectricityRates($voltage, $current, $rate);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bootstrap Example - Calculate Electricity</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">

<div class="container mt-5 mb-5">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h3 class="mb-0">Calculate</h3>
        </div>
        <div class="card-body">
            <form action="" method="POST">
                <div class="form-group">
                    <label for="voltage">Voltage (V)</label>
                    <input type="number" step="any" name="voltage" id="voltage" class="form-control" value="<?php echo htmlspecialchars($voltage); ?>" required>
                </div>
                <div class="form-group">
                    <label for="current">Current (Ampere (A))</label>
                    <input type="number" step="any" name="current" id="current" class="form-control" value="<?php echo htmlspecialchars($current); ?>" required>
                </div>
                <div class="form-group">
                    <label for="rate">Current Rate (sen/kWh)</label>
                    <input type="number" step="any" name="rate" id="rate" class="form-control" value="<?php echo htmlspecialchars($rate); ?>" required>
                </div>
                <button type="submit" class="btn btn-primary btn-block">Calculate</button>
            </form>
        </div>
    </div>

    <?php if ($results): ?>
        <div class="card mt-4 shadow-sm">
            <div class="card-body bg-dark text-white font-weight-bold">
                <p class="mb-1">POWER: <?php echo number_format($results['power_kw'], 5); ?> kW</p>
                <p class="mb-0">RATE: <?php echo number_format($results['rate_rm'], 3); ?> RM</p>
            </div>
        </div>

        <div class="mt-4">
            <h4 class="mb-3">Calculated Hourly Consumption (1 - 24 Hours)</h4>
            <div class="table-responsive">
                <table class="table table-striped table-bordered bg-white">
                    <thead class="thead-dark">
                        <tr>
                            <th>#</th>
                            <th>Hour</th>
                            <th>Energy (kWh)</th>
                            <th>TOTAL (RM)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($results['hourly_data'] as $row): ?>
                            <tr>
                                <td><?php echo $row['hour']; ?></td>
                                <td><?php echo $row['hour']; ?></td>
                                <td><?php echo number_format($row['energy'], 5); ?></td>
                                <td><?php echo number_format($row['total'], 2); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endif; ?>
</div>

</body>
</html>