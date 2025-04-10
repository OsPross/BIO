document.addEventListener('DOMContentLoaded', function() {
    let visits = localStorage.getItem('visitCount');
    
    if (!visits) {
        visits = 0;
    }
  
    if (!localStorage.getItem('hasVisited')) {
        visits++;
        localStorage.setItem('visitCount', visits);
        localStorage.setItem('hasVisited', 'true');
    }
  
    document.getElementById('view-count').textContent = visits;
  });