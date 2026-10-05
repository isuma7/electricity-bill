<?php
// ==========================================================
//  process.php = THE LOGIC
//  Reads the input, checks it, and calculates the bill.
//  There is no HTML in this file. It only prepares variables
//  and index.php displays them.
// ==========================================================

$error    = "";      // the warning message ("" means no error)
$showBill = false;   // true = show the bill, false = do not show

// what the user typed in each box (empty when the page first opens)
$block1 = "";        // 1 - 200 kWh
$block2 = "";        // 201 - 300 kWh
$block3 = "";        // 301 - 600 kWh
$block4 = "";        // 601 - 900 kWh
$block5 = "";        // 901 kWh onwards


// Run only when the "Calculate Bill" button was clicked.
// The Reset button is named "reset", not "calculate", so clicking
// it skips everything below and the variables stay empty.
if (isset($_POST['calculate'])) {

    // --- get the data from the form ---
    $block1 = $_POST['block1'];
    $block2 = $_POST['block2'];
    $block3 = $_POST['block3'];
    $block4 = $_POST['block4'];
    $block5 = $_POST['block5'];

    // an empty box means that block was not used, so make it 0
    if ($block1 == "") { $block1 = 0; }
    if ($block2 == "") { $block2 = 0; }
    if ($block3 == "") { $block3 = 0; }
    if ($block4 == "") { $block4 = 0; }
    if ($block5 == "") { $block5 = 0; }


    // --- validation ---
    // PHP checks these from top to bottom and stops at the first
    // one that is true. If none are true, the else at the bottom
    // runs and the bill is calculated.

    // is_numeric() is true if the value is a number. ! means NOT.
    if (!is_numeric($block1) || !is_numeric($block2) || !is_numeric($block3)
        || !is_numeric($block4) || !is_numeric($block5)) {
        $error = "Please enter numbers only. Try again.";
    }
    // a meter reading cannot be less than zero
    else if ($block1 < 0 || $block2 < 0 || $block3 < 0 || $block4 < 0 || $block5 < 0) {
        $error = "kWh cannot be a negative number. Try again.";
    }
    // each block can only hold a fixed amount of kWh
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
    // $block5 has no limit because the tariff says "901 kWh onwards"

    // the user must enter something
    else if ($block1 + $block2 + $block3 + $block4 + $block5 == 0) {
        $error = "Please enter your electricity usage.";
    }

    // every check passed, so calculate the bill
    else {

        // --- charge for each block (kWh x rate) ---
        $charge1 = $block1 * 0.218;
        $charge2 = $block2 * 0.344;
        $charge3 = $block3 * 0.516;
        $charge4 = $block4 * 0.546;
        $charge5 = $block5 * 0.571;

        // --- totals ---
        $totalKwh    = $block1 + $block2 + $block3 + $block4 + $block5;
        $totalCharge = $charge1 + $charge2 + $charge3 + $charge4 + $charge5;

        // the minimum monthly charge is RM3.00
        if ($totalCharge < 3.00) {
            $totalCharge = 3.00;
        }

        // --- 6% SST only when usage is more than 600 kWh ---
        if ($totalKwh > 600) {
            $sst = $totalCharge * 0.06;
        } else {
            $sst = 0;
        }

        // --- the final bill ---
        $totalBill = $totalCharge + $sst;

        $showBill = true;   // tell index.php to display the bill
    }
}
