<!-- flip-card-container -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
 <?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
?>
<div class="flip-card-container" style="--hue: 220">
  <div class="flip-card">

    <div class="card-front">
      <figure>
        <div class="img-bg"></div>
        <img src="assets\images\logo.jpg" alt="Brohm Lake">
        <figcaption>About Us</figcaption>
      </figure>

      <ul>
      <li>
  <i class="fa-solid fa-file gradient-icon" style="font-size: 180px;"></i>
</li>

      </ul>
    </div>

    <div class="card-back">
      <figure>
        <div class="img-bg"></div>
        <img src="assets\images\logo.jpg" alt="Brohm Lake">
      </figure>
      <div class="content-container">
     
  <ul>    <?php

$ret=mysqli_query($con,"select * from tblpage where PageType='aboutus' ");
$cnt=1;
while ($row=mysqli_fetch_array($ret)) {

?>
      <li><?php  echo $row['PageDescription'];?></li>
      
      <?php } ?></ul><br>
  <button>BOOK NOW</button>
      <div class="design-container">
        <span class="design design--1"></span>
        <span class="design design--2"></span>
        <span class="design design--3"></span>
        <span class="design design--4"></span>
        <span class="design design--5"></span>
        <span class="design design--6"></span>
        <span class="design design--7"></span>
        <span class="design design--8"></span>
      </div>
    </div>
    </div>
  </div>
</div>
<!-- /flip-card-container -->



<a href="https://abubakersaeed.netlify.app/designs/d4-flip-card" class="abs-site-link" rel="nofollow noreferrer" target="_blank">abs/designs/d4-flip-card</a>

<style>

    /* 
    ================================
        Best Viewed In Full Page
    ================================
*/

/* Hover over a card to flip, can tab too. */

@import url('https://fonts.googleapis.com/css?family=Lato');

/* default */
*,
*::after,
*::before {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

/* body */
body {
  min-height: 100vh;
  padding: 40px;

  display: flex;
  flex-wrap: wrap;
  justify-content: center;
  align-items: center;

  background: hsl(220, 10%, 12%);
  font-family: "Lato", "Segoe Ui", -apple-system, BlinkMacSystemFont, sans-serif;
}

/* .flip-card-container */
.flip-card-container {
  --hue: 150;
  --primary: hsl(var(--hue), 50%, 50%);
  --white-1: hsl(0, 0%, 90%);
  --white-2: hsl(0, 0%, 80%);
  --dark: hsl(var(--hue), 25%, 10%);
  --grey: hsl(0, 0%, 50%);

  width: 310px;
  height: 500px;
  margin: 40px;

  perspective: 1000px;
}

/* .flip-card */
.flip-card {
  width: inherit;
  height: inherit;

  position: relative;
  transform-style: preserve-3d;
  transition: .6s .1s;
}

/* hover and focus-within states */
.flip-card-container:hover .flip-card,
.flip-card-container:focus-within .flip-card {
  transform: rotateY(180deg);
}

/* .card-... */
.flip-card .card-front,
.flip-card .card-back {
  width: 100%;
  height: 100%;
  border-radius: 24px;

  background: var(--dark);
  position: absolute;
  top: 0;
  left: 0;
  overflow: hidden;

  backface-visibility: hidden;

  display: flex;
  justify-content: center;
  align-items: center;
}

/* .card-front */
.flip-card  .card-front {
  transform: rotateY(0deg);
  z-index: 2;
}

/* .card-back */
.flip-card  .card-back {
  transform: rotateY(180deg);
  z-index: 1;
}
.flip-card  .content-container {
  display: flex;
  flex-direction: column;
  align-items: center; /* Center items horizontally */
  margin-top: 20px; /* Optional: add some space above the content */
}

.flip-card .content-container button {
  margin-bottom: 10px; /* Optional: space between button and list */
}
/* figure */
.flip-card figure {
  z-index: -1;
}

/* figure, .img-bg */
.flip-card figure,
.flip-card .img-bg {
  position: absolute;
  top: 0;
  left: 0;

  width: 100%;
  height: 100%;
}

/* img */
.flip-card img {
  height: 100%;
  border-radius: 24px;
  width: 180%;
}



.flip-card .card-back .img-bg {
  clip-path: polygon(0 0, 100% 0, 100% 80%, 0 60%);
}

/* hover state */
.flip-card-container:hover .card-front .img-bg::before {
  width: 6px;
  border-left-color: var(--primary);
  border-right-color: var(--primary);
}
.flip-card .gradient-icon {
  font-size: 48px; /* Adjust size as needed */
  background: linear-gradient(20deg, white, pink);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
}
/* ul */
ul {
  margin: 0 auto;
  width: 70%;
  height: 100%;

  list-style: none;
  color: black;

  display: flex;
  justify-content: center;
  align-items: center;
  flex-direction: column;
}

/* li */
li {
  width: 100%;
  margin-top: 12px;
  padding-bottom: 12px;

  font-size: 14px;
  text-align: center;

  position: relative;
  transition: text-shadow 0.3s ease; 
}

li:nth-child(2n) {
  color: var(--white-2);
}

li:not(:last-child)::after {
  content: "";

  position: absolute;
  bottom: 0;
  left: 0;

  width: 100%;
  height: 1px;

  background: currentColor;
  opacity: .2;
}
/* Apply text shadow on hover */
li:hover {
    text-shadow: 2px 2px 4px rgba(255, 255, 255, 0.5); /* Strong white text shadow on hover */
}
/* button */
.flip-card  button {
  font-family: inherit;
  font-weight: bold;
  color: black;

  letter-spacing: 2px;

  padding: 9px 20px;
  border: 1px solid var(--grey);
  border-radius: 1000px;
  background: transparent;
  transition: .3s;

  cursor: pointer;
}

.flip-card button:hover,
.flip-card button:focus {
  color: var(--primary);
  background: hsla(var(--hue), 25%, 10%, .2);
  border-color: currentColor;
}

.flip-card button:active {
  transform: translate(2px);
}

/* .design-container */
.flip-card .design-container {
  --tr: 90;
  --op: .5;

  width: 100%;
  height: 100%;

  background: transparent;
  position: absolute;
  top: 0;
  left: 0;

  pointer-events: none;
}

/* .design */
.flip-card .design {
  display: block;

  background: var(--grey);
  position: absolute;

  opacity: var(--op);
  transition: .3s;
}

.design--1,
.design--2,
.design--3,
.design--4 {
  width: 1px;
  height: 100%;
}

.design--1,
.design--2 {
  top: 0;
  transform: translateY(calc((var(--tr) - (var(--tr) * 2)) * 1%))
}

.design--1 {
  left: 20%;
}

.design--2 {
  left: 80%;
}

.design--3,
.design--4 {
  bottom: 0;
  transform: translateY(calc((var(--tr) + (var(--tr) - var(--tr))) * 1%))
}

.design--3 {
  left: 24%;
}

.design--4 {
  left: 76%;
}

.design--5,
.design--6,
.design--7,
.design--8 {
  width: 100%;
  height: 1px;
}

.design--5,
.design--6 {
  left: 0;
  transform: translateX(calc((var(--tr) - (var(--tr) * 2)) * 1%));
}

.design--5 {
  top: 41%;
}

.design--6 {
  top: 59%;
}

.design--7,
.design--8 {
  right: 0;
  transform: translateX(calc((var(--tr) + (var(--tr) - var(--tr))) * 1%))
}

.design--7 {
  top: 44%;
}

.design--8 {
  top: 56%;
}

/* states */
button:hover+.design-container,
button:active+.design-container,
button:focus+.design-container {
  --tr: 20;
  --op: .7;
}

.abs-site-link {
  position: fixed;
  bottom: 20px;
  left: 20px;
  color: hsla(0, 0%, 100%, .6);
  font-size: 16px;
  font-family: 'Segoe UI', -apple-system, BlinkMacSystemFont, sans-serif;
}
</style>