var etoile2 = document.querySelectorAll(".etoile2");
var clickedIndex2 = -1; // Pour garder une trace de l'index de l'étoile cliquée
var nbr_etoile2 = document.querySelector("#nbr-etoile2");

etoile2.forEach((star, index) => {
    // Fonction pour changer la couleur des étoiles jusqu'à un certain index
    const colorStars = (limit, color) => {
        etoile2.forEach((s, i) => {
            s.setAttribute("fill", i <= limit ? color : "none");
        });
    };

    // Gestionnaire pour le survol
    star.onmouseenter = function() {
        colorStars(index, "#F6A427"); // Couleur pour les étoiles survolées
    };

    // Gestionnaire pour le clic
    star.onclick = function() {
        clickedIndex2 = index; // Mémoriser l'index de l'étoile cliquée
        colorStars(index, "#F6A427"); // Couleur pour les étoiles cliquées
        nbr_etoile2.value = index + 1; // Mettre à jour la valeur de #nbr-etoile2
    };

    // Gestionnaire pour la sortie du survol
    star.onmouseleave = function() {
        colorStars(clickedIndex2, "#F6A427"); // Maintenir la couleur des étoiles cliquées
    };
});
