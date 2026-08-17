
/* ---------- Home Page ---------- */

const hero = document.querySelector(".hero-section");

if (hero) {

    const images = [
        "images/home1.jpeg",
        "images/home2.jpeg",
        "images/home3.jpeg",
        "images/home4.jpeg"
    ];

    let index = 0;

    function changeBackground() {

        hero.style.backgroundImage =
        `linear-gradient(rgba(0,0,0,.55),rgba(0,0,0,.55)),
        url('${images[index]}')`;

        index = (index + 1) % images.length;
    }

    changeBackground();

    setInterval(changeBackground,4000);
}


/* ---------- Booking Page ---------- */

const bookingHero = document.querySelector(".booking-hero");

const images = [
    "images/hero1.jpeg",
    "images/hero2.jpeg",
    "images/hero3.jpeg",
    "images/hero4.jpeg"
];

let current = 0;

function changeBackground(){

    if(!bookingHero) return;

    bookingHero.style.backgroundImage =
    `linear-gradient(rgba(0,0,0,0.5),rgba(0,0,0,0.5)),
    url('${images[current]}')`;

    current++;

    if(current >= images.length){
        current = 0;
    }
}

changeBackground();

setInterval(changeBackground,4000);