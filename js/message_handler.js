"use strict";
// checks every 2000 seconds if there is a message displayed,
// if yes it will remove it so we can display newer messages
// endless thread as long as the page is live:
setInterval(() => {
  if (document.querySelector(".message") !== null) {
    document.querySelector(".message").remove();
  }
}, 3000);
