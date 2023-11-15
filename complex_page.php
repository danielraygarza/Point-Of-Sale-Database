<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Overly Complex Webpage</title>
    <style>
        /* Some complex styling rules */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }

        header {
            background-color: #333;
            color: #fff;
            padding: 1em;
            text-align: center;
        }

        section {
            margin: 20px;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        footer {
            background-color: #333;
            color: #fff;
            padding: 1em;
            text-align: center;
            margin-top: auto;
        }
    </style>
</head>
<body>

<header>
    <h1>Welcome to the Overly Complex Webpage</h1>
</header>

<section>
    <h2>Interactive Section</h2>
    <button onclick="changeColor()">Click me to change color!</button>
</section>

<section>
    <h2>Long Content Section</h2>
    <!-- Repeating a long paragraph for demonstration purposes -->
    <?php
    for ($i = 0; $i < 20; $i++) {
        echo "<p>This is a long paragraph. It repeats to make the section look complex. </p>\n";
    }
    ?>
</section>

<footer>
    <p>&copy; 2023 Overly Complex Webpage. All rights reserved.</p>
</footer>

<script>
    // Some complex JavaScript logic
    function changeColor() {
        const randomColor = '#' + Math.floor(Math.random()*16777215).toString(16);
        document.body.style.backgroundColor = randomColor;
    }
</script>

</body>
</html>
