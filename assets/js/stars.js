const background = document.getElementById("background");

for (let i = 0; i < 100; i++) {
  const star = document.createElement("div");
  star.classList.add("star");
  star.style.left = `${Math.random() * 100}%`;
  star.style.top = `${Math.random() * 100}%`;
  star.style.animationDelay = `${Math.random() * 2}s`;
  star.style.width = `${Math.random() * 5 + 1}px`; 
  star.style.height = star.style.width;
  star.style.opacity = Math.random();
  background.appendChild(star);
}

setTimeout(() => {
  document.body.classList.add("fade-in");
}, 1000);

