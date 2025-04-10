function updateDateTime() {
    const now = new Date();
    const options = { year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' };
    const dateTimeString = now.toLocaleDateString('en-US', options);
    document.getElementById('datetime').textContent = dateTimeString;
  }
  
  updateDateTime();
  setInterval(updateDateTime, 1000);