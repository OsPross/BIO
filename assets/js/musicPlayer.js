document.addEventListener('DOMContentLoaded', function() {
    const audioPlayer = document.getElementById('audio-player');
    const playPauseBtn = document.getElementById('play-pause-btn');
    const volumeControl = document.getElementById('volume-control');
    const currentTrack = document.getElementById('current-track');

    // Ustaw ścieżkę do pliku audio
    if (window.audioConfig && window.audioConfig.trackPath) {
        audioPlayer.src = window.audioConfig.trackPath;
    } else {
        // Domyślna ścieżka jeśli konfiguracja nie istnieje
        audioPlayer.src = 'assets/voice_effects/default_track.mp3';
        currentTrack.textContent = 'Default Track';
    }

    // Obsługa przycisku play/pause
    playPauseBtn.addEventListener('click', function() {
        if (audioPlayer.paused) {
            audioPlayer.play()
                .then(() => {
                    playPauseBtn.innerHTML = '<i class="fas fa-pause"></i>';
                })
                .catch(error => {
                    console.error('Błąd odtwarzania:', error);
                    currentTrack.textContent = 'Błąd odtwarzania';
                });
        } else {
            audioPlayer.pause();
            playPauseBtn.innerHTML = '<i class="fas fa-play"></i>';
        }
    });

    // Obsługa głośności
    volumeControl.addEventListener('input', function() {
        audioPlayer.volume = this.value;
    });

    // Obsługa błędów
    audioPlayer.addEventListener('error', function() {
        console.error('Błąd audio:', audioPlayer.error);
        currentTrack.textContent = 'Błąd ładowania utworu';
    });
});