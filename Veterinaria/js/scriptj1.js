    /*** A lot of debugging work... ***/

function elem(id) {
  var e = document.getElementById(id);
  return e;
}

var dog = elem("dog");
var allSheep = document.querySelectorAll(".sheep-zone");

/** Random Position Sheep **/
for (var i = 0; i < allSheep.length; i++) {
  var posRight = window.innerWidth - 50 - 120;
  var posLeft = Math.random() * window.innerWidth - allSheep[i].offsetWidth;
  if (posLeft < 50) posLeft = 50;
  if (posLeft > posRight) posLeft = posRight - 120;

  var posBottom = window.innerHeight - 50 - 120;
  var posTop = Math.random() * window.innerHeight - allSheep[i].offsetHeight;
  if (posTop < 50) posTop = 50;
  if (posTop > posBottom) posTop = posBottom - 120;

  allSheep[i].style.left = posLeft + "px";
  allSheep[i].style.top = posTop + "px";
}

/*** CSS Tricks - Chris Coyier - Calculate Distance ***/
// https://css-tricks.com/snippets/jquery/calculate-distance-between-mouse-and-element/

(function() {

  var mX,
      mY,
      distance,
      fencedistance,
      sheepdistance,
      sheep = document.querySelectorAll(".sheep-zone"),
      fence = document.querySelector(".sheep-pen");

  function calculateDistance(elem, mouseX, mouseY) {
    return Math.floor(
      Math.sqrt(
        Math.pow(mouseX - (elem.offsetLeft + elem.offsetWidth / 2), 2) +
        Math.pow(mouseY - (elem.offsetTop + elem.offsetHeight / 2), 2)
      )
    );
  }

  /*** Mouse Listener ***/
  mousemovemethod = function (e) {
    for (var i = 0; i < sheep.length; i++) {
      mX = e.pageX;
      mY = e.pageY;

      distance = calculateDistance(sheep[i], mX, mY);

      //sheep distance
      for (var x = 0; x < sheep.length; x++) {
        sheepdistance = calculateDistance(
          sheep[i],
          Math.round(
            sheep[x].getBoundingClientRect().left + sheep[x].offsetWidth / 2
          ),
          Math.round(sheep[x].getBoundingClientRect().top + sheep[x].offsetHeight / 2)
        );

        if (sheep[i] != sheep[x]) {
          if (sheepdistance < 100) sheep[i].classList.add("alone");
          else sheep[i].classList.remove("alone");
        }
      }

      //fence distance
      fencedistance = calculateDistance(
        sheep[i],
        Math.round(fence.getBoundingClientRect().left + fence.offsetWidth / 2),
        Math.round(fence.getBoundingClientRect().top + fence.offsetHeight / 2)
      );
      if (fencedistance < 100) sheep[i].classList.add("catched");
      else sheep[i].classList.remove("catched");

      //add/remove class for scared sheep
      if (distance < 50) sheep[i].classList.add("scared");
      else sheep[i].classList.remove("scared");

      //if the sheep is near dog
      if (distance < 100) {
        centerSheepX = sheep[i].offsetWidth / 2;
        centerSheepY = sheep[i].offsetHeight / 2;

        posSheepX = Math.round(sheep[i].style.left.slice(0, -2)) + centerSheepX;
        posSheepY = Math.round(sheep[i].style.top.slice(0, -2)) + centerSheepY;

        //sheep pos X
        if (mX < posSheepX) sheep[i].style.left = mX + 60 + "px";
        if (mX > posSheepX) sheep[i].style.left = mX - 180 + "px";

        //sheep pos Y
        if (mY < posSheepY) sheep[i].style.top = mY + 60 + "px";
        if (mY > posSheepY) sheep[i].style.top = mY - 180 + "px";
      }
    } // for each sheep end

    /*** Dog Movement ***/
    dog.style.left = e.clientX + "px";
    dog.style.top = e.clientY + "px";
  };



})();



document.addEventListener("mousemove", mousemovemethod);

/*** Dog Direction ***/
var direction = "",
    oldx = (oldy = 0),
    dogmovemethod = function (e) {
      if (e.pageY < oldy) {
        directionY = "top";
      } else if (e.pageY > oldy) {
        directionY = "bottom";
      }

      if (e.pageX < oldx) {
        directionX = "left";
      } else if (e.pageX > oldx) {
        directionX = "right";
      }

      dog.className = directionY + " " + directionX;

      oldx = e.pageX;
      oldy = e.pageY;
    };

document.addEventListener("mousemove", dogmovemethod);

/***  Count Losses and Trapped ***/
var countSheep = setInterval(function () {
  elem("trap").classList.remove("show");
  elem("lost").classList.remove("show");

  var lostSheep = 0;

  for (var i = 0; i < allSheep.length; i++) {
    if (allSheep[i].style.left.slice(0, -2) < -100) {
      allSheep[i].style.left = -200 + "px";
      lostSheep++;
    } else if (allSheep[i].style.top.slice(0, -2) < -100) {
      allSheep[i].style.top = -200 + "px";
      lostSheep++;
    } else if (allSheep[i].style.left.slice(0, -2) > window.innerWidth) {
      allSheep[i].style.left = window.innerWidth + 200 + "px";
      lostSheep++;
    } else if (allSheep[i].style.top.slice(0, -2) > window.innerHeight) {
      allSheep[i].style.top = window.innerWidth + 200 + "px";
      lostSheep++;
    }
  }

  //update trapped
  if (elem("trap").innerText != document.querySelectorAll(".catched").length)
    elem("trap").classList.add("show");
  elem("trap").innerText = document.querySelectorAll(".catched").length;

  //update losses
  if (elem("lost").innerText != lostSheep) elem("lost").classList.add("show");
  elem("lost").innerText = lostSheep;

  /* game over */
  if (
    lostSheep + document.querySelectorAll(".catched").length ==
    allSheep.length
  ) {
    clearInterval(countSheep);

    var m = document.querySelectorAll(".menu");
    for (var i = 0; i < m.length; i++) {
      m[i].classList.add("end");
    }

    document.removeEventListener("mousemove", mousemovemethod);

    var finalText;

    switch (lostSheep) {
      case allSheep.length:
        finalText = "¡QUÉ DESASTRE!!<br><br>¡TODAS LAS OVEJAS PERDIDAS!";
        break;
      case 0:
        finalText = "¡EXCELENTE TRABAJO!<br><br>TODAS LAS OVEJAS ESTÁN A SALVO";
        break;
      case 1:
        finalText = "¡BUEN TRABAJO!<br><br>SOLO " + lostSheep + " OVEJAS PERDIDAS";
        break;
      case 2:
        finalText = "¡BIEN HECHO!<br><br>AUNQUE" + lostSheep + " LAS OVEJAS SE PERDIERON";
        break;
      case 3:
        finalText = "OPS!<br><br> " + lostSheep + " OVEJA PERDIDA, NO ESTÁ BIEN";
        break;
      case 4:
        finalText = "OOPS!<br><br> " + lostSheep + " OVEJA PERDIDA, NO ES BUENO";
        break;
      case 5:
        finalText =
          "OOOPS!<br><br> " + lostSheep + " OVEJAS PERDIDA, NO ES NASA BUENO EN LO ABSOLUTO";
        break;
      case 6:
        finalText = "NOOOOO!<br><br> PERDISTE LA MITAD DEL REBAÑO";
        break;
      case 7:
        finalText = "¿EN SERIO <br><br> ¿PERDISTE " + lostSheep + " OVEJAS?";
        break;
      case 8:
        finalText =
          "¡NO PUEDE SER!<br><br> PERDISTE " + lostSheep + " OVEJAS, ¿EN SERIO?";
        break;
      case 9:
        finalText =
          "¡NO LO CREO!<br><br> PERDISTE " +
          lostSheep +
          " OVEJAS, ¿ESTÁS BROMANDO?";
        break;
      case 10:
        finalText =
          "NO NO NO!<br><br> TU PERDISTE " + lostSheep + " OVEJAS, ¿ESTÁIS TONTO?";
        break;
      case 11:
        finalText = "¡INCREÍBLE!<br><br>¡SOLO SALVASTE A  1 OVEJA!";
        break;
      default:
        finalText = "GAME OVER";
        break;
    }

    elem("msg").style.opacity = 1;
    elem("msg").innerHTML = finalText;
  }
}, 1000);
