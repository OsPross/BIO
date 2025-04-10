<!DOCTYPE html>
<html>
<head>
    <title>Gra w Kości</title>
    <link rel="stylesheet" href="assets/css/ruleta.css">
</head>
<body>
<div id="background"></div>
<div id="datetime"></div>
<button class="wroc"><a href="profile.php?login=true">Wróć</a></button>
    <div class="container">
        <h1>Gra w Kości ze Zdzisiem</h1>

        <div class="dice-container">
            <div class="dice-box">
                <div class="label">Ja:</div>
                <div id="playerDice" class="dice"></div>
            </div>
            <div class="dice-box">
                <div class="label">Zdzisiu:</div>
                <div id="botDice" class="dice"></div>
            </div>
        </div>

        <div id="result"></div>
        <button onclick="rollDice()">Rzuć Kośćmi</button>


    </div>

    <script src='assets/js/ruleta.js'></script>
    <script src="assets/js/stars.js"></script> 
    <script src="assets/js/datetime.js"></script>
</body>
</html>