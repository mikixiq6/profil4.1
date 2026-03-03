<?php
$jsonFilePath = 'profile.json';
$profile = [];

if (file_exists($jsonFilePath)) {
    $jsonData = file_get_contents($jsonFilePath);
    $profile = json_decode($jsonData, true) ?? [];
}

$message = "";
$messageType = "";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["new_interest"])) {
    $newInterest = trim($_POST["new_interest"]);

    if (!empty($newInterest)) {
        if (!isset($profile['interests'])) {
            $profile['interests'] = [];
        }

        $lowerNewInterest = strtolower($newInterest);
        $existingInterestsLower = array_map('strtolower', $profile['interests']);

        if (!in_array($lowerNewInterest, $existingInterestsLower)) {
            $profile['interests'][] = $newInterest;
            if (file_put_contents($jsonFilePath, json_encode($profile, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE))) {
                $message = "Zájem byl úspěšně přidán.";
                $messageType = "success";
            }
        } else {
            $message = "Tento zájem už existuje.";
            $messageType = "error";
        }
    } else {
        $message = "Pole nesmí být prázdné.";
        $messageType = "error";
    }
}
?>
<!DOCTYPE html>
<html lang="cs">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IT Profile - <?php echo htmlspecialchars($profile['name'] ?? 'Unknown'); ?></title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            margin: 40px auto;
            max-width: 800px;
            background-color: #f0f2f5;
            color: #333;
            text-align: center;
        }

        h1 {
            color: #1a1a1a;
            font-size: 2.5em;
            margin-bottom: 30px;
        }

        h2 {
            color: #4a4a4a;
            padding-bottom: 10px;
            margin-top: 40px;
        }

        ul {
            padding-left: 0;
            list-style-type: none;
        }

        li {
            padding: 5px 0;
        }

        .success {
            color: #155724;
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
            padding: 12px;
            border-radius: 8px;
            font-weight: bold;
            margin: 20px auto;
            max-width: 400px;
        }

        .error {
            color: #721c24;
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            padding: 12px;
            border-radius: 8px;
            font-weight: bold;
            margin: 20px auto;
            max-width: 400px;
        }

        form {
            margin: 30px auto;
            padding: 25px;
            border-radius: 12px;
            background: white;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        input[type="text"] {
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 16px;
            outline: none;
            transition: border-color 0.3s;
        }

        input[type="text"]:focus {
            border-color: #007bff;
        }

        button {
            padding: 12px 20px;
            border: none;
            background-color: #007bff;
            color: white;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #0056b3;
        }

        .bubble-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 12px;
            padding: 0;
            margin-top: 20px;
        }

        .bubble {
            background: linear-gradient(135deg, #007BFF, #00d2ff);
            color: white;
            padding: 8px 18px;
            border-radius: 25px;
            font-weight: 500;
            font-size: 1em;
            box-shadow: 0 4px 10px rgba(0, 123, 255, 0.3);
            transition: transform 0.2s;
        }

        .bubble:hover {
            transform: translateY(-2px);
        }
    </style>
</head>

<body>
    <h1><?php echo htmlspecialchars($profile['name'] ?? 'N/A'); ?></h1>

    <?php if (!empty($message)): ?>
        <p class="<?php echo $messageType; ?>">
            <?php echo htmlspecialchars($message); ?>
        </p>
    <?php endif; ?>

    <form method="POST">
        <input type="text" name="new_interest" placeholder="Enter a new interest..." required>
        <button type="submit">Přidat zájem</button>
    </form>

    <?php if (!empty($profile['skills'])): ?>
        <h2>Dovednosti (Skills)</h2>
        <ul>
            <?php foreach ($profile['skills'] as $skill): ?>
                <li><?php echo htmlspecialchars($skill); ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <?php if (!empty($profile['projects'])): ?>
        <h2>Projekty (Projects)</h2>
        <ul>
            <?php foreach ($profile['projects'] as $project): ?>
                <li><?php echo htmlspecialchars($project); ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <?php if (!empty($profile['interests'])): ?>
        <h2>Zájmy (Interests)</h2>
        <div class="bubble-container">
            <?php foreach ($profile['interests'] as $interest): ?>
                <span class="bubble"><?php echo htmlspecialchars($interest); ?></span>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</body>

</html>