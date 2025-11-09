var etoile = document.querySelectorAll(".etoile");
var clickedIndex = -1; // Keep track of the clicked star index

etoile.forEach((star, index) => {
    // Hover effect: color stars up to the hovered one
    star.onmouseenter = function() {
        for (let i = 0; i <= index; i++) {
            etoile[i].setAttribute("fill", "#F6A427"); // Color for hovered stars
        }
    };

    // Click event: lock in the star rating
    star.onclick = function() {
        var nbr_etoile = document.querySelector("#nbr-etoile");
        clickedIndex = index; // Store the clicked star index
        for (let i = 0; i <= index; i++) {
            etoile[i].setAttribute("fill", "#F6A427"); // Color for clicked stars
            nbr_etoile.value = i + 1; // Update hidden input with star count
        }
    };

    // Mouse leave: restore the clicked stars, reset the others
    star.onmouseleave = function() {
        for (let i = 0; i < etoile.length; i++) {
            if (i <= clickedIndex) {
                etoile[i].setAttribute("fill", "#F6A427"); // Keep clicked stars colored
            } else {
                etoile[i].setAttribute("fill", "none"); // Reset others
            }
        }
    };
});
