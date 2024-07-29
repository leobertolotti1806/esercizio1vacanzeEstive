let parola;
let tentativi = 6;
let guessedLetters = [];

async function getParola() {
    let response = await fetch('server.php');
    let data = await response.json();
    parola = data.parola;
    displayWord();
    console.log("PAROLA => " + parola);
}

function displayWord() {
    let hangman = document.getElementById('hangman');
    hangman.innerHTML = '';

    for (let char of parola) {
        if (guessedLetters.includes(char)) {
            hangman.innerHTML += char + ' ';
        } else {
            hangman.innerHTML += '_ ';
        }
    }

    if (!hangman.innerHTML.includes('_')) {
        alert('Hai vinto!');
        resetGame();
    }
}

function guessLetter(letter) {
    guessedLetters.push(letter);
    if (!parola.includes(letter)) {
        tentativi--;
        if (tentativi === 0) {
            alert('Hai perso! La parola era: ' + parola);
            resetGame();
        }
    }
    displayWord();
}

function createButtons() {
    let lettersDiv = document.getElementById('letters');
    lettersDiv.innerHTML = '';
    let letters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';

    for (let letter of letters) {
        let button = document.createElement('button');
        button.classList.add('letter');
        button.innerHTML = letter;
        button.onclick = () => {
            button.disabled = true;
            guessLetter(letter.toLowerCase());
        };
        lettersDiv.appendChild(button);
    }
}

function resetGame() {
    tentativi = 6;
    guessedLetters = [];
    createButtons();
    getParola();
}


createButtons();
getParola();