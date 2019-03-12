window.onscroll = function() {
    stickyHeader();
  };
  
  let header = document.getElementById("header");
  let sticky = header.offsetTop;
  
  function stickyHeader() {
    if (window.pageYOffset > sticky) {
      header.classList.add("sticky");
    } else {
      header.classList.remove("sticky");
    }
  }