let selected_bord = document.querySelectorAll(".selected_bord");
let selected_bord_body = document.querySelectorAll(".selected_bord_body");

selected_bord.forEach((component, index) => {
    component.onclick = function() {
        let selectedBody = selected_bord_body[index];
        let isOpen = selectedBody.classList.contains('open');

        if (!isOpen) {
            // Afficher le contenu avec une animation de défilement
            selectedBody.classList.remove('hidden'); // Retire la classe cachée pour l'afficher
            selectedBody.style.height = selectedBody.scrollHeight + "px";
            selectedBody.classList.add('open'); // Applique la classe 'open' pour l'animation

            // Ajouter la classe active pour changer le fond et le texte
            component.classList.add('active');
        } else {
            // Fermer avec animation
            selectedBody.style.height = selectedBody.scrollHeight + "px"; // Fixe la hauteur actuelle
            selectedBody.offsetHeight; // Déclenche un reflow pour s'assurer que la transition est prise en compte
            selectedBody.style.height = "0px"; // Réduire la hauteur à 0 pour l'animation

            // Une fois l'animation terminée, cacher l'élément
            setTimeout(() => {
                selectedBody.classList.remove('open');
                selectedBody.classList.add('hidden'); // Cacher après la transition
            }, 500); // La durée correspond au temps de transition

            // Retirer la classe active pour remettre l'état initial
            component.classList.remove('active');
        }
    };
});
