<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Math Calculator</title>
</head>
<body>
    <h2>Simple Math Calculator</h2>
    <form action="" method="post">
        <label for="number1">Number 1:</label>
        <input type="number" name="number1" id="number1" required>
        <br><br>

        <label for="operation">Operation:</label>
        <select name="operation" id="operation">
            <option value="add">Addition (+)</option>
            <option value="subtract">Subtraction (-)</option>
            <option value="multiply">Multiplication (×)</option>
            <option value="divide">Division (÷)</option>
        </select>
        <br><br>

        <label for="number2">Number 2:</label>
        <input type="number" name="number2" id="number2" required>
        <br><br>

        <input type="submit" name="calculate" value="Calculate">
    </form>

    <?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $number1 = $_POST['number1'];
        $operation = $_POST['operation'];
        $number2 = $_POST['number2'];

        $result = '';

        switch ($operation) {
            case 'add':
                $result = $number1 + $number2;
                break;
            case 'subtract':
                $result = $number1 - $number2;
                break;
            case 'multiply':
                $result = $number1 * $number2;
                break;
            case 'divide':
                if ($number2 == 0) {
                    $result = "Division by zero error!";
                } else {
                    $result = $number1 / $number2;
                }
                break;
            default:
                $result = "Invalid operation selected.";
                break;
        }

        echo "<h3>Result: $result</h3>";
    }
    ?>
</body>
</html>
