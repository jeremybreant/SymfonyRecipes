// assets/controllers/form-collection_controller.js

import { Controller } from '@hotwired/stimulus';

export default class extends Controller {

    static values = {
        recipeid: Number
    }
    static targets = ["icon"]

    toggleFav() {

        let favIcon = this.iconTarget;
        this.toggleFavIcon(favIcon);
        this.toggleFavInDB();

    }

    toggleFavIcon(favIcon){
        if (favIcon.getAttribute('data-prefix') === 'far') {
            favIcon.setAttribute('data-prefix', 'fas');
            favIcon.classList.add("animate__heartBeat");
        } else {
            favIcon.setAttribute('data-prefix', 'far');
            favIcon.classList.remove("animate__heartBeat");
        }
    }

    toggleFavInDB() {
        console.log(this.recipeidValue);
        $.ajax({
            url: '/toggle-recipe-fav',
            method: 'POST',
            data: {
                recipeId: this.recipeidValue,
            },
            //*
            success: function(response) {
                // Mettre à jour l'apparence du bouton en fonction de la réponse
                if (response.success) {
                    console.log("toggle effectué avec succès")
                } else {
                    // Gérer les erreurs de manière appropriée
                    console.error('Erreur lors de la mise à jour du favori : ' + response.error);
                }
            },
            //*/
            error: function(xhr, status, error) {
                // Gérer les erreurs de manière appropriée
                console.error('Erreur lors de la requête AJAX : ' + error);
            }
        });
    }
}
