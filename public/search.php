<?php
require '../vendor/autoload.php';

use App\Validator;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $search_term = $_POST['search_term'] ?? '';
    $validator = new Validator();

    $result = '';
    if ($validator->isXSS($search_term)) {
        $result = 'Invalid input detected. Possible XSS attack.<br><a href="/">Return to Home</a>';
    } elseif ($validator->isSQLInjection($search_term)) {
        $result = 'Invalid input detected. Possible SQL Injection.<br><a href="/">Return to Home</a>';
    } else {
        $result = 'Search Term: ' . htmlspecialchars($search_term) . '<br><a href="/">Return to Home</a>';
    }
} else {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Result</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="container">
        <?php echo $result; ?>
    </div>
</body>
</html>
