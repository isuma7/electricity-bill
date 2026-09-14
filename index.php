<?php
// ==========================================================================
//  index.php  =  THE VIEW
//  CRM 1963 - Web Programming with PHP
//
//  This file only DISPLAYS things on the screen.
//  There is no calculation and no validation inside this file.
//
//  All the thinking is done inside process.php. The line below loads that
//  file and runs it FIRST, before any HTML is sent to the browser. After it
//  finishes, these variables are ready for this page to display:
//
//      $error       the warning message, or "" when there is no error
//      $showBill    true when the bill should be displayed
//      $block1..5   the kWh the user typed in each box
//      $charge1..5  the RM amount for each block
//      $totalKwh    the total kWh used
//      $totalCharge the total RM before SST
//      $sst         the 6% SST amount
//      $totalBill   the final amount to pay
// ==========================================================================

include 'process.php';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Calculate House Electricity Bill</title>

    <!-- link the CSS file that makes the page look nice -->
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h1>Calculate House Electricity Bill</h1>


<!-- =====================================================================
     PART 1: THE TARIFF TABLE
     This is just plain HTML. It shows the user the rate for each block
     so they know how the bill is calculated.
     ===================================================================== -->
<div class="box">
    <h2>Block Tariff (per month)</h2>

    <table>
        <!-- the heading row of the table -->
        <tr>
            <th>Block Tariff (per month)</th>
            <th>Unit</th>
            <th>Rate</th>
        </tr>

        <!-- one row for each block of the tariff -->
        <tr><td>For the first 200 kWh (1 - 200 kWh)</td><td>sen/kWh</td><td>0.218</td></tr>
        <tr><td>For the next 100 kWh (201 - 300 kWh)</td><td>sen/kWh</td><td>0.344</td></tr>
        <tr><td>For the next 300 kWh (301 - 600 kWh)</td><td>sen/kWh</td><td>0.516</td></tr>
        <tr><td>For the next 300 kWh (601 - 900 kWh)</td><td>sen/kWh</td><td>0.546</td></tr>
        <tr><td>For the next kWh (901 kWh onwards)</td><td>sen/kWh</td><td>0.571</td></tr>
    </table>

    <p>Minimum monthly charge is RM3.00. Usage more than 600 kWh is charged 6% SST.</p>
</div>


<!-- =====================================================================
     PART 2: THE INPUT FORM
     method="post" means the data is sent using $_POST.
     There is no action="" so the form sends the data back to this same
     page, and process.php reads it again from the top.
     ===================================================================== -->
<div class="box">
    <h2>Enter Your Electricity Usage</h2>

    <?php
    // Show the red warning box ONLY if $error is not empty.
    // $error was prepared by process.php.  != means "is not equal to".
    if ($error != "") {
    ?>
        <p class="error">Warning: <?php echo $error; ?></p>
    <?php
    }   // end of the if
    ?>

    <form method="post">

        <!-- TEXT BOX 1
             name="block1"  -> this is the name process.php uses when it
                               reads $_POST['block1']
             value="..."    -> print the old value back into the box so the
                               user does not lose what they typed -->
        <p>
            Enter your first 200 kWh (1 - 200 kWh) per month :
            <input type="text" name="block1" value="<?php echo $block1; ?>"> kWh
        </p>

        <!-- TEXT BOX 2 -->
        <p>
            Enter next 100 kWh (201 - 300 kWh) per month :
            <input type="text" name="block2" value="<?php echo $block2; ?>"> kWh
        </p>

        <!-- TEXT BOX 3 -->
        <p>
            Enter next 300 kWh (301 - 600 kWh) per month :
            <input type="text" name="block3" value="<?php echo $block3; ?>"> kWh
        </p>

        <!-- TEXT BOX 4 -->
        <p>
            Enter next 300 kWh (601 - 900 kWh) per month :
            <input type="text" name="block4" value="<?php echo $block4; ?>"> kWh
        </p>

        <!-- TEXT BOX 5 -->
        <p>
            Enter next kWh (901 kWh onwards) per month :
            <input type="text" name="block5" value="<?php echo $block5; ?>"> kWh
        </p>

        <p>
            <!-- The submit button. Its name="calculate" is what the
                 isset($_POST['calculate']) inside process.php checks. -->
            <input type="submit" name="calculate" value="Calculate Bill">

            <!-- The reset button clears all the boxes. This is done by the
                 browser itself, so no PHP code is needed for it. -->
            <input type="reset" value="Reset">
        </p>

    </form>
</div>


<!-- =====================================================================
     PART 3: THE BILL
     This whole section is displayed ONLY when $showBill is true.
     process.php sets it to true after the user enters correct data.
     ===================================================================== -->
<?php if ($showBill) { ?>

<div class="box">
    <h2>Your Electricity Bill</h2>

    <table>
        <!-- heading row -->
        <tr>
            <th>Block</th>
            <th>kWh Used</th>
            <th>Rate</th>
            <th>Amount</th>
        </tr>

        <!-- One row for each block. echo prints the value of a variable.
             number_format($charge1, 2) prints the number with exactly
             2 decimal places, for example 43.6 becomes 43.60 -->
        <tr>
            <td>1 - 200 kWh</td>
            <td><?php echo $block1; ?> kWh</td>
            <td>0.218</td>
            <td>RM <?php echo number_format($charge1, 2); ?></td>
        </tr>
        <tr>
            <td>201 - 300 kWh</td>
            <td><?php echo $block2; ?> kWh</td>
            <td>0.344</td>
            <td>RM <?php echo number_format($charge2, 2); ?></td>
        </tr>
        <tr>
            <td>301 - 600 kWh</td>
            <td><?php echo $block3; ?> kWh</td>
            <td>0.516</td>
            <td>RM <?php echo number_format($charge3, 2); ?></td>
        </tr>
        <tr>
            <td>601 - 900 kWh</td>
            <td><?php echo $block4; ?> kWh</td>
            <td>0.546</td>
            <td>RM <?php echo number_format($charge4, 2); ?></td>
        </tr>
        <tr>
            <td>901 kWh onwards</td>
            <td><?php echo $block5; ?> kWh</td>
            <td>0.571</td>
            <td>RM <?php echo number_format($charge5, 2); ?></td>
        </tr>
    </table>

    <!-- The summary of the bill -->

    <!-- total kWh used in the month -->
    <p>Total electricity consumption : <b><?php echo $totalKwh; ?> kWh</b></p>

    <!-- total charge in RM before SST -->
    <p>Total consumption : <b>RM <?php echo number_format($totalCharge, 2); ?></b></p>

    <!-- the 6% SST (this shows RM 0.00 when the usage is 600 kWh or less) -->
    <p>SST 6% : <b>RM <?php echo number_format($sst, 2); ?></b></p>

    <!-- the final amount the customer must pay -->
    <p class="total">Total Current Bill : RM <?php echo number_format($totalBill, 2); ?></p>
</div>

<?php }   // end of the if ($showBill) ?>

</body>
</html>
