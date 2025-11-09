var imageInput2 = document.querySelectorAll('#imageInput2');
var imagePreview2 = document.querySelectorAll('#imagePreview2');
var removeimage2  = document.querySelectorAll('.removeimage2');

imageInput2.forEach((input, index) => {
    input.onchange = function(event) {
        // Récupère le fichier sélectionné
        const [file] = event.target.files;
        
        // Si un fichier est sélectionné
        if (file) {
            // Met à jour la source de l'image
            imagePreview2[index].src = URL.createObjectURL(file);
            
            // Affiche l'image et le bouton de suppression
            imagePreview2[index].style.display = 'block';
            removeimage2[index].style.display = 'block';
        }
    };
});

removeimage2.forEach((closeImage, index) => {
    closeImage.onclick = function(event) {
        // Réinitialise la source de l'image
        imagePreview2[index].src = defaultImageUrl;
        
        // Cache le bouton de suppression
        closeImage.style.display = 'none';
    };
});
