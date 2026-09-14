# Calculate House Electricity Bill

CRM 1963 - Web Programming with PHP
Practical Test (July 2023)

## Files

- `process.php` - THE LOGIC: reads the input, validates it, calculates the bill
- `index.php` - THE VIEW: displays the form and the bill on screen
- `style.css` - styling

`index.php` loads `process.php` with `include 'process.php';` at the very top.
The logic runs first, then the page displays the result. `process.php` has no
HTML inside it, and `index.php` has no calculation inside it.

## How to run

Put the folder inside `~/Herd/` and open <http://electricity-bill.test>

Or run it with the built-in PHP server:

```bash
php -S localhost:8000
```

## Tariff used

| Block | Rate |
|---|---|
| First 200 kWh (1 - 200) | 0.218 |
| Next 100 kWh (201 - 300) | 0.344 |
| Next 300 kWh (301 - 600) | 0.516 |
| Next 300 kWh (601 - 900) | 0.546 |
| Next kWh (901 onwards) | 0.571 |

- Minimum monthly charge: RM3.00
- SST 6% is added when usage is more than 600 kWh

## Example from the question paper (780 kWh)

| Block | kWh | Amount |
|---|---|---|
| 1 - 200 | 200 | RM 43.60 |
| 201 - 300 | 100 | RM 34.40 |
| 301 - 600 | 300 | RM 154.80 |
| 601 - 900 | 180 | RM 98.28 |
| **Total consumption** | **780 kWh** | **RM 331.08** |
| SST 6% | | RM 19.86 |
| **Total Current Bill** | | **RM 350.94** |

## Validation

The form shows a warning and asks the user to enter the data again when:

- the input is not a number
- the input is a negative number
- the input is bigger than the block size (example: more than 200 in the first block)
- nothing is entered at all

## Note about the question paper

Two numbers in the paper do not match each other:

1. SST - the paper says "exceeds 600kWH" in one place and "more than 700 kWh"
   in another. This system uses 600 kWh.
2. Minimum charge - the paper prints "RM300", which looks like a typo for
   RM3.00. This system uses RM3.00.

Both are easy to change inside `index.php` if the examiner wants the other value.
