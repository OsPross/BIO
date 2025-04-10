function rollDice() {
    const playerRoll = Math.floor(Math.random() * 6) + 1;
    const botRoll = Math.floor(Math.random() * 6) + 1;

    document.getElementById("playerDice").textContent = playerRoll;
    document.getElementById("botDice").textContent = botRoll;

    let result = "";
    if (playerRoll > botRoll) {
        result = "Wygrałem Ja!";
    } else if (playerRoll < botRoll) {
        result = "Wygrał Zdzisu!";
    } else {
        result = "Remis!";
    }

    document.getElementById("result").textContent = result;
}