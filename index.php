<?php
// ==========================================================
//  index.php = THE VIEW
//  This file only displays things. No calculation here.
//  The line below runs process.php first, which prepares
//  $error, $showBill, $block1..5, $charge1..5, $totalKwh,
//  $totalCharge, $sst and $totalBill.
// ==========================================================

include 'process.php';
?>
<html>

<head>
    <title>Calculate House Electricity Bill</title>
</head>

<body>

    <h2>Calculate House Electricity Bill</h2>

    <?php
    // show the warning only when there is an error
    if ($error != "") {
        echo "<p style='color: red;'><b>Warning:</b> " . $error . "</p>";
    }
    ?>

    <!-- method="post" sends the data to process.php.
     No action="" means it is sent back to this same page. -->

    <div style="margin-bottom: 50px">
        <table border="1">
            <tr>
                <th>Block Tariff (per month)</th>
                <th>Unit</th>
                <th>Rate</th>
            </tr>
            <tr>
                <td>For the first 200 kWh (1-200 kWh) per month</td>
                <td>1 - 200 kWh</td>
                <td>0.218</td>
            </tr>
            <tr>
                <td>For the first 100 kWh (201-300 kWh) per month</td>
                <td>201 - 300 kWh</td>
                <td>0.344</td>
            </tr>
            <tr>
                <td>For the first 300 kWh (301-600 kWh) per month</td>
                <td>301 - 600 kWh</td>
                <td>0.516</td>
            </tr>
            <tr>
                <td>For the first 300 kWh (601-900 kWh) per month</td>
                <td>601 - 900 kWh</td>
                <td>0.546</td>
            </tr>
            <tr>
                <td>For the first 300 kWh (901 kWh onwards) per month</td>
                <td>901 kWh onwards</td>
                <td>0.571</td>
            </tr>
        </table>
    </div>

    <form method="post">

        Enter your first 200 kWh (1 - 200 kWh) per month :
        <input type="text" name="block1" value="<?php echo $block1; ?>"><br><br>

        Enter next 100 kWh (201 - 300 kWh) per month :
        <input type="text" name="block2" value="<?php echo $block2; ?>"><br><br>

        Enter next 300 kWh (301 - 600 kWh) per month :
        <input type="text" name="block3" value="<?php echo $block3; ?>"><br><br>

        Enter next 300 kWh (601 - 900 kWh) per month :
        <input type="text" name="block4" value="<?php echo $block4; ?>"><br><br>

        Enter next kWh (901 kWh onwards) per month :
        <input type="text" name="block5" value="<?php echo $block5; ?>"><br><br>

        <!-- name="calculate" is what process.php checks with isset() -->
        <input type="submit" name="calculate" value="Calculate Bill">

        <!-- named "reset" so process.php skips the calculation and
         the boxes come back empty -->
        <input type="submit" name="reset" value="Reset">

    </form>

    <?php
    // show the bill only after the input is correct
    if ($showBill) {

        echo "<hr>";
        echo "<h3>Your Electricity Bill</h3>";

        // one line for each block: kWh x rate = amount
        // number_format($charge1, 2) shows 2 decimal places (43.6 -> 43.60)
        echo "1 - 200 kWh : " . $block1 . " kWh x 0.218 = RM " . number_format($charge1, 2) . "<br>";
        echo "201 - 300 kWh : " . $block2 . " kWh x 0.344 = RM " . number_format($charge2, 2) . "<br>";
        echo "301 - 600 kWh : " . $block3 . " kWh x 0.516 = RM " . number_format($charge3, 2) . "<br>";
        echo "601 - 900 kWh : " . $block4 . " kWh x 0.546 = RM " . number_format($charge4, 2) . "<br>";
        echo "901 kWh onwards : " . $block5 . " kWh x 0.571 = RM " . number_format($charge5, 2) . "<br>";

        echo "<br>";

        // the summary
        echo "Total electricity consumption : " . $totalKwh . " kWh<br>";
        echo "Total consumption : RM " . number_format($totalCharge, 2) . "<br>";
        echo "SST 6% : RM " . number_format($sst, 2) . "<br>";
        echo "<b>Total Current Bill : RM " . number_format($totalBill, 2) . "</b><br>";
    }
    ?>

</body>

</html>