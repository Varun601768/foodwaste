async function fetchBackgroundImages() {
    const response = await fetch('get_images.php');
    const images = await response.json();

    let currentIndex = 0;
    const banner = document.getElementById('banner');

    function changeBackground() {
        if (images.length > 0) {
            banner.style.backgroundImage = `url(${images[currentIndex]})`;
            currentIndex = (currentIndex + 1) % images.length;
        }
    }

    // Change background every 5 seconds
    setInterval(changeBackground, 5000);

    // Set the initial background
    changeBackground();
}

fetchBackgroundImages();
