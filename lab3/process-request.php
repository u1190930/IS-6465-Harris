<?php

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    exit("Method not allowed. Submit the tutoring request form using POST.");
}

$course = trim($_POST["course"] ?? "");
$topic = trim($_POST["topic"] ?? "");
$preferredTime = $_POST["preferredtime"] ?? "";
$preferredDate = $_POST["preferreddate"] ?? "";

$errors = [];
$allowedCourses = ["IS 6465", "IS 6495", "IS 6570"];
$allowedTimes = ["Morning", "Afternoon", "Evening"];

if (!in_array($course, $allowedCourses, true)) {
    $errors["course"] = "Select a valid course.";
}

if (!in_array($preferredTime, $allowedTimes, true)) {
    $errors["preferred_time"] = "Select a valid preferred time.";
}




if ($topic === "" || mb_strlen($topic) > 120) {
    $errors["topic"] = "Enter a topic of 1 to 120 characters.";
}

$date = is_string($preferredDate)
    ? DateTimeImmutable::createFromFormat("!Y-m-d", $preferredDate)
    : false;
$validDate = $date && $date->format("Y-m-d") === $preferredDate;

if (!$validDate) {
    $errors["preferred_date"] = "Enter a valid preferred date.";
} elseif ($date < new DateTimeImmutable("today")) {
    $errors["preferred_date"] = "Preferred date cannot be in the past.";
}

function escapeHtml(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8");
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tutoring Request</title>
</head>
<body>
    <main>
        <?php if ($errors): ?>
            <h1>Unable to process request</h1>
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?= escapeHtml($error) ?></li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <h1>Request received</h1>
            <p>Course: <?= escapeHtml($course) ?></p>
            <p>Topic: <?= escapeHtml($topic) ?></p>
            <p>Preferred date: <?= escapeHtml($preferredDate) ?></p>
            <p>Preferred time: <?= escapeHtml($preferredTime) ?></p>
            <p>Status: Pending review</p>
        <?php endif; ?>
    </main>
</body>
</html>
