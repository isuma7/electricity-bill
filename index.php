<?php
// ==========================================================================
//  CALCULATE HOUSE ELECTRICITY BILL
//  CRM 1963 - Web Programming with PHP
//
//  HOW THIS PAGE WORKS:
//  1. The user opens the page and sees an empty form.
//  2. The user types the kWh used and clicks "Calculate Bill".
//  3. The form sends the data back to THIS SAME PAGE using method="post".
//  4. PHP checks the data. If it is wrong, a warning is shown.
//  5. If the data is correct, PHP calculates and displays the bill.
// ==========================================================================


// --------------------------------------------------------------------------
//  SECTION 1: PREPARE THE VARIABLES
//  These are set up first so the page still works the very first time it is
//  opened, before the user has submitted anything.
// --------------------------------------------------------------------------

$error    = "";     // holds the warning message. Empty "" means no error yet.
$showBill = false;  // false = do not show the bill. true = show the bill.

// These 5 variables hold what the user typed in each text box.
// They start empty so the boxes are empty when the page first loads.
// After a wrong input, PHP puts these values back into the boxes so the
// user does not have to type everything again.
$block1 = "";       // first 200 kWh    (1 - 200 kWh)
$block2 = "";       // next 100 kWh     (201 - 300 kWh)
$block3 = "";       // next 300 kWh     (301 - 600 kWh)
$block4 = "";       // next 300 kWh     (601 - 900 kWh)
$block5 = "";       // next kWh         (901 kWh onwards)


// --------------------------------------------------------------------------
//  SECTION 2: CHECK IF THE FORM WAS SUBMITTED
//  isset() returns true if the variable exists.
//  $_POST['calculate'] only exists after the user clicks the button named
//  "calculate". So this whole block is SKIPPED when the page first loads.
// --------------------------------------------------------------------------

if (isset($_POST['calculate'])) {

    // ----------------------------------------------------------------------
    //  SECTION 3: GET THE INPUT FROM THE FORM
    //  $_POST is a built-in PHP array that holds everything the form sent.
    //  The name inside the [ ] must match the name="" of the input box.
    // ----------------------------------------------------------------------

    $block1 = $_POST['block1'];   // read the value typed in the 1st box
    $block2 = $_POST['block2'];   // read the value typed in the 2nd box
    $block3 = $_POST['block3'];   // read the value typed in the 3rd box
    $block4 = $_POST['block4'];   // read the value typed in the 4th box
    $block5 = $_POST['block5'];   // read the value typed in the 5th box

    // If the user leaves a box empty, it means they did not use that block.
    // An empty box "" cannot be multiplied, so we change it to the number 0.
    if ($block1 == "") { $block1 = 0; }
    if ($block2 == "") { $block2 = 0; }
    if ($block3 == "") { $block3 = 0; }
    if ($block4 == "") { $block4 = 0; }
    if ($block5 == "") { $block5 = 0; }


    // ----------------------------------------------------------------------
    //  SECTION 4: VALIDATION (check the input before calculating)
    //  The question paper says: every wrong input must print a warning and
    //  ask the user to enter the data once more.
    //
    //  We use "if ... else if ... else". PHP checks them from top to bottom
    //  and stops at the FIRST one that is true. If none of them are true,
    //  the final "else" runs and the bill is calculated.
    // ----------------------------------------------------------------------

    // CHECK 1: make sure every box contains a number.
    // is_numeric() returns true if the value is a number.
    // The ! symbol means NOT, so !is_numeric() means "is NOT a number".
    // The || symbol means OR, so this is true if ANY box is not a number.
    if (!is_numeric($block1) || !is_numeric($block2) || !is_numeric($block3)
        || !is_numeric($block4) || !is_numeric($block5)) {

        $error = "Please enter numbers only. Try again.";
    }

    // CHECK 2: a meter reading can never be less than zero.
    else if ($block1 < 0 || $block2 < 0 || $block3 < 0 || $block4 < 0 || $block5 < 0) {

        $error = "kWh cannot be a negative number. Try again.";
    }

    // CHECK 3 to 6: each block can only hold a fixed amount of kWh.
    // Example: the first block is 1 - 200 kWh, so 250 does not fit inside it.
    else if ($block1 > 200) {
        $error = "The first block is only 200 kWh. Please enter 200 or less.";
    }
    else if ($block2 > 100) {
        $error = "The second block is only 100 kWh. Please enter 100 or less.";
    }
    else if ($block3 > 300) {
        $error = "The third block is only 300 kWh. Please enter 300 or less.";
    }
    else if ($block4 > 300) {
        $error = "The fourth block is only 300 kWh. Please enter 300 or less.";
    }
    // NOTE: $block5 has no maximum because the tariff says "901 kWh onwards".

    // CHECK 7: the user must enter something. If every box is 0, there is
    // nothing to calculate.
    else if ($block1 + $block2 + $block3 + $block4 + $block5 == 0) {
        $error = "Please enter your electricity usage.";
    }

    // If we reach this "else", every check above passed, so the input is
    // correct and we can now calculate the bill.
    else {

        // ------------------------------------------------------------------
        //  SECTION 5: CALCULATE THE CHARGE FOR EACH BLOCK
        //  Formula for every block:   kWh used  x  rate  =  amount in RM
        // ------------------------------------------------------------------

        $charge1 = $block1 * 0.218;   // 1 - 200 kWh       at RM0.218 per kWh
        $charge2 = $block2 * 0.344;   // 201 - 300 kWh     at RM0.344 per kWh
        $charge3 = $block3 * 0.516;   // 301 - 600 kWh     at RM0.516 per kWh
        $charge4 = $block4 * 0.546;   // 601 - 900 kWh     at RM0.546 per kWh
        $charge5 = $block5 * 0.571;   // 901 kWh onwards   at RM0.571 per kWh


        // ------------------------------------------------------------------
        //  SECTION 6: ADD EVERYTHING UP
        // ------------------------------------------------------------------

        // Total kWh used in the month (add the 5 boxes together).
        $totalKwh = $block1 + $block2 + $block3 + $block4 + $block5;

        // Total charge in RM (add the 5 block charges together).
        $totalCharge = $charge1 + $charge2 + $charge3 + $charge4 + $charge5;

        // The minimum monthly charge is RM3.00. If the customer used very
        // little electricity and the charge is below RM3.00, they still have
        // to pay RM3.00.
        if ($totalCharge < 3.00) {
            $totalCharge = 3.00;
        }


        // ------------------------------------------------------------------
        //  SECTION 7: SST (Sales and Service Tax)
        //  6% SST is charged only when the usage is MORE THAN 600 kWh.
        // ------------------------------------------------------------------

        if ($totalKwh > 600) {
            $sst = $totalCharge * 0.06;   // 6% of the total charge
        } else {
            $sst = 0;                     // no SST for 600 kWh or below
        }


        // ------------------------------------------------------------------
        //  SECTION 8: THE FINAL BILL
        // ------------------------------------------------------------------

        // The customer pays the charge plus the SST.
        $totalBill = $totalCharge + $sst;

        // Change this to true so the bill section at the bottom of the page
        // will be displayed.
        $showBill = true;
    }
}
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
     There is no action="" so the form sends the data back to this same page.
     ===================================================================== -->
<div class="box">
    <h2>Enter Your Electricity Usage</h2>

    <?php
    // Show the red warning box ONLY if $error is not empty.
    // != means "is not equal to".
    if ($error != "") {
    ?>
        <p class="error">Warning: <?php echo $error; ?></p>
    <?php
    }   // end of the if
    ?>

    <form method="post">

        <!-- TEXT BOX 1
             name="block1"  -> this is the name PHP uses in $_POST['block1']
             value="..."    -> PHP prints the old value back into the box so
                               the user does not lose what they typed -->
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
                 isset($_POST['calculate']) at the top of this page checks. -->
            <input type="submit" name="calculate" value="Calculate Bill">

            <!-- The reset button clears all the boxes. This is done by the
                 browser itself, so no PHP code is needed for it. -->
            <input type="reset" value="Reset">
        </p>

    </form>
</div>


<!-- =====================================================================
     PART 3: THE BILL
     This whole section is displayed ONLY when $showBill is true, which
     happens after the user enters correct data.
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
