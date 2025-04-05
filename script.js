const images = [
    'img/b1.jpg',
    'img/b2.jpg'
    
];

let currentIndex = 0;
const banner = document.getElementById('banner');

function changeBackground() {
    banner.style.backgroundImage = `url(${images[currentIndex]})`;
    currentIndex = (currentIndex + 1) % images.length; // Cycle through the images
}

// Change background every 5 seconds
setInterval(changeBackground, 5000);

// Set the initial background
changeBackground();
