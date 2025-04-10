document.addEventListener('DOMContentLoaded', function() {
    // Funkcja obsługująca pojedynczy formularz przesyłania plików
    function setupFileUpload(formId, inputId, statusId, browseBtnClass, uploadBoxClass) {
        const fileInput = document.getElementById(inputId);
        const fileStatus = document.getElementById(statusId);
        const browseBtn = document.querySelector(`#${formId} .${browseBtnClass}`);
        const uploadBox = document.querySelector(`#${formId} .${uploadBoxClass}`);

        if (fileInput && fileStatus && browseBtn && uploadBox) {
            // Kliknięcie przycisku "Wybierz plik"
            browseBtn.addEventListener('click', function() {
                fileInput.click();
            });

            // Zmiana wybranego pliku
            fileInput.addEventListener('change', function(e) {
                updateFileStatus(e.target.files, fileStatus);
            });

            // Obsługa przeciągania plików
            uploadBox.addEventListener('dragover', function(e) {
                e.preventDefault();
                this.classList.add('drag-over');
                fileStatus.textContent = 'Upuść plik tutaj';
            });

            uploadBox.addEventListener('dragleave', function() {
                this.classList.remove('drag-over');
                updateFileStatus(fileInput.files, fileStatus);
            });

            uploadBox.addEventListener('drop', function(e) {
                e.preventDefault();
                this.classList.remove('drag-over');
                fileInput.files = e.dataTransfer.files;
                updateFileStatus(fileInput.files, fileStatus);
            });
        }
    }

    // Funkcja aktualizująca status pliku
    function updateFileStatus(files, statusElement) {
        if (files.length > 0) {
            statusElement.textContent = `Wybrano: ${files[0].name}`;
            
            // Dodatkowe informacje dla plików audio (rozmiar)
            if (files[0].type.startsWith('audio/')) {
                statusElement.textContent += ` (${formatFileSize(files[0].size)})`;
            }
        } else {
            statusElement.textContent = 'Nie wybrano pliku';
        }
    }

    // Funkcja formatująca rozmiar pliku
    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2) + ' ' + sizes[i]);
    }

    // Inicjalizacja formularza zdjęć profilowych
    setupFileUpload(
        'profile-image-form', 
        'profile_image', 
        'profile-file-status', 
        'browse-btn', 
        'file-upload-box'
    );

    // Inicjalizacja formularza plików audio
    setupFileUpload(
        'music-upload-form', 
        'track_name', 
        'audio-file-status', 
        'browse-btn', 
        'file-upload-box'
    );
});