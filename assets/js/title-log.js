var titles = [
    "@",
    "@L",
    "@Lo",
    "@Log",
    "@Logo",
    "@Logow",
    "@Logowa",
    "@Logowan",
    "@Logowani",
    "@Logowanie",
    "@Logowanie",
    "@Logowanie",
    "@Logowani",
    "@Logowan",
    "@Logowa",
    "@Logow",
    "@Logo",
    "@Log",
    "@Lo",
    "@L",
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