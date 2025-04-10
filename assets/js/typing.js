document.addEventListener('DOMContentLoaded', function () {
    const descriptionElement = document.getElementById('profile-description');
    const descriptionText = descriptionElement.textContent;

    descriptionElement.innerHTML = ''; 

    let i = 0;
    const typingInterval = setInterval(() => {
        if (i < descriptionText.length) {
            descriptionElement.innerHTML += descriptionText.charAt(i);
            i++;
        } else {
            clearInterval(typingInterval);
        }
    }, 50); 
});