var imageInput = document.querySelectorAll('#imageInput');
var imagePreview = document.querySelectorAll('#imagePreview');
var removeimage  = document.querySelectorAll('.removeimage');

imageInput.forEach((input, index) => {
    input.onchange = function(event) {
        // Récupère le fichier sélectionné
        const [file] = event.target.files;
        
        // Si un fichier est sélectionné
        if (file) {
            // Met à jour la source de l'image
            imagePreview[index].src = URL.createObjectURL(file);
            
            // Affiche l'image et le bouton de suppression
            imagePreview[index].style.display = 'block';
            removeimage[index].style.display = 'block';
        }
    };
});

removeimage.forEach((closeImage, index) => {
    closeImage.onclick = function(event) {
        // Réinitialise la source de l'image
        imagePreview[index].src = defaultImageUrl;
        
        // Cache le bouton de suppression
        closeImage.style.display = 'none';
    };
});
