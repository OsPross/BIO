var titles = [
    "@",
    "@O",
    "@Os",
    "@OsP",
    "@OsPr",
    "@OsPro",
    "@OsPros",
    "@OsPros",
    "@OsPros",
    "@OsPro",
    "@OsPr",
    "@OsP",
    "@Os",
    "@O",
    "@",
  ];
  
  function changeTitle() {
    var index = 0;
    setInterval(function() {
      document.title = titles[index];
      index = (index + 1) % titles.length;
    }, 300);
  }
  
  changeTitle();