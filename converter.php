<?php
// Function to convert number to English words
function numberToWords($number) {
    $ones = array(
        0 => "Zero", 1 => "One", 2 => "Two", 3 => "Three", 4 => "Four", 5 => "Five", 6 => "Six",
        7 => "Seven", 8 => "Eight", 9 => "Nine", 10 => "Ten", 11 => "Eleven", 12 => "Twelve",
        13 => "Thirteen", 14 => "Fourteen", 15 => "Fifteen", 16 => "Sixteen", 17 => "Seventeen",
        18 => "Eighteen", 19 => "Nineteen"
    );
    $tens = array(
        2 => "Twenty", 3 => "Thirty", 4 => "Forty", 5 => "Fifty", 6 => "Sixty",
        7 => "Seventy", 8 => "Eighty", 9 => "Ninety"
    );
    $thousands = array(
        "", "Thousand", "Million", "Billion", "Trillion"
    );

    if ($number == 0) {
        return "Zero";
    }

    $words = "";
    $chunks = array();
    while ($number > 0) {
        $chunks[] = $number % 1000;
        $number = (int)($number / 1000);
    }

    $numChunks = count($chunks);

    for ($i = $numChunks - 1; $i >= 0; $i--) {
        if ($chunks[$i] > 0) {
            $chunkWords = "";
            $hundreds = (int)($chunks[$i] / 100);
            $tensOnes = $chunks[$i] % 100;

            if ($hundreds > 0) {
                $chunkWords .= $ones[$hundreds] . " Hundred ";
            }

            if ($tensOnes > 0) {
                if ($tensOnes < 20) {
                    $chunkWords .= $ones[$tensOnes];
                } else {
                    $chunkWords .= $tens[(int)($tensOnes / 10)];
                    if ($tensOnes % 10 > 0) {
                        $chunkWords .= "" . $ones[$tensOnes % 10];
                    }
                }
            }

            if ($i > 0) {
                $chunkWords .= " " . $thousands[$i];
            }

            $words .= $chunkWords . " ";
        }
    }

    return trim($words);
}

function numberToKhmerWords($number) {
    // Adjusted to handle '0' correctly
    $ones = array("០", "មួយ", "ពីរ", "បី", "បួន", "ប្រាំ", "ប្រាំមួយ", "ប្រាំពីរ", "ប្រាំបី", "ប្រាំបួន");
    $tens = array("", "ដប់", "ម្ភៃ", "សាមសិប", "សែសិប", "ហាសិប", "ហុកសិប", "ចិតសិប", "ប៉ែតសិប", "កៅសិប");
    $thousands = array("", "ពាន់", "លាន", "ប៊ីលាន", "ទ្រីលាន");

    if ($number == 0) {
        return "សូន្យ"; // Ensure zero is handled correctly
    }

    $words = "";
    $chunks = array();
    $i = 0;

    while ($number > 0) {
        $chunks[] = $number % 1000;
        $number = (int)($number / 1000);
        $i++;
    }

    $numChunks = count($chunks);

    for ($i = $numChunks - 1; $i >= 0; $i--) {
        if ($chunks[$i] > 0) {
            $chunkWords = "";
            $hundreds = (int)($chunks[$i] / 100);
            $tensOnes = $chunks[$i] % 100;

            if ($hundreds > 0) {
                $chunkWords .= $ones[$hundreds] . " រយ ";
            }

            if ($tensOnes > 0) {
                if ($tensOnes < 10) {
                    $chunkWords .= $ones[$tensOnes];
                } else if ($tensOnes < 20) {
                    $chunkWords .= "ដប់ " . $ones[$tensOnes % 10];
                } else {
                    $chunkWords .= $tens[(int)($tensOnes / 10)];
                    if ($tensOnes % 10 > 0) {
                        $chunkWords .= "" . $ones[$tensOnes % 10];
                    }
                }
            }

            $words .= $chunkWords . " " . $thousands[$i] . " ";
        }
    }

    return trim($words);
}


$output = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $riel = isset($_POST["riel"]) ? $_POST["riel"] : null;

    if ($riel !== null && is_numeric($riel)) {
        $englishWords = numberToWords($riel) . " Riel";
        $khmerWords = numberToKhmerWords($riel) . " រៀល";
        $usd = number_format($riel / 4000, 2) . "$";

        // Save to text file
        file_put_contents("current_projects.txt", "Riel: $riel, English: $englishWords, Khmer: $khmerWords, USD: $usd\n", FILE_APPEND);

        $output = "
        <div class='output'>
            <p><strong>Input:</strong> $riel</p>
            <p><strong>English:</strong> $englishWords</p>
            <p><strong>Khmer:</strong> $khmerWords</p>
            <p><strong>USD:</strong> $usd</p>
        </div>";
    } else {
        $output = "<p style='color:red;'>Please enter a valid number.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Number to Words Converter</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            text-align: center;
            padding: 20px;
        }
        .container {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 50%;
            margin: auto;
        }
        input[type="text"], input[type="submit"] {
            padding: 10px;
            margin: 10px 0;
            width: 80%;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        input[type="submit"] {
            background-color: #007bff;
            color: white;
            border: none;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #0056b3;
        }
        .output {
            background-color: #e7f7ff;
            padding: 20px;
            border-radius: 10px;
            margin-top: 20px;
            display: inline-block;
            text-align: left;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Number to Words Converter</h1>
        <form method="post">
            <input type="text" name="riel" id="riel" placeholder="Enter number here" required>
            <input type="submit" value="Submit">
        </form>
        <?php echo $output; ?>
    </div>
</body>
</html>
