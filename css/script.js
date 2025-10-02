const imgBoxes = document.querySelectorAll('.img-box');
imgBoxes.forEach(box => {
    box.addEventListener('click', function () {
        const imageUrl = this.getAttribute('data-bs-image');
        const modalImage = document.getElementById('modalImage');
        modalImage.src = imageUrl; // Set the modal image source to the clicked image's source
    });
});
