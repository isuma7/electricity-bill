<?php
// ==========================================================================
//  process.php  =  THE LOGIC
//  CRM 1963 - Web Programming with PHP
//
//  This file does all the THINKING:
//      - reads the data the user typed
//      - checks that the data is correct  (validation)
//      - calculates the bill
//
//  This file has NO HTML inside it. It never prints anything on the screen.
//  It only prepares the variables and index.php displays them later.
//
//  index.php loads this file with the line:   include 'process.php';
// ==========================================================================


// --------------------------------------------------------------------------
//  SECTION 1: PREPARE THE VARIABLES
//  These are set up first so index.php always has something to display,
//  even the very first time the page is opened.
// --------------------------------------------------------------------------

$error    = "";     // holds the warning message. Empty "" means no error yet.
$showBill = false;  // false = do not show the bill. true = show the bill.

// These 5 variables hold what the user typed in each text box.
// They start empty so the boxes are empty when the page first loads.
// After a wrong input, index.php puts these values back into the boxes so
// the user does not have to type everything again.
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
//
//  This is also how the RESET button works. The Reset button is named
//  "reset", not "calculate", so when the user clicks it this if is false and
//  the whole block below is skipped. Every variable keeps the empty value it
//  was given in Section 1, which makes the boxes blank again and hides the
//  bill. No extra code is needed to clear the form.
// --------------------------------------------------------------------------

if (isset($_POST['calculate'])) {

    // ----------------------------------------------------------------------
    //  SECTION 3: GET THE INPUT FROM THE FORM
    //  $_POST is a built-in PHP array that holds everything the form sent.
    //  The name inside the [ ] must match the name="" of the input box
    //  inside index.php.
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

        // Change this to true. index.php checks this variable to decide
        // whether to display the bill section or not.
        $showBill = true;
    }
}
