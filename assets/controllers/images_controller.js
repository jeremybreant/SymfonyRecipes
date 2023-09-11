// assets/controllers/images_controller.js

import { Controller } from '@hotwired/stimulus';

export default class extends Controller {

    static values = {
        imageid: Number
    }
    static targets = ["image"];

    deleteImages() {
        console.log(this.imageidValue);
        let imageTarget = this.imageTarget;
        $.ajax({
            url: '/ingredient/suppression/image',
            method: 'POST',
            data: {
                imageId: this.imageidValue,
            },
            //*
            success: function (response) {
                // Mettre à jour l'apparence du bouton en fonction de la réponse
                if (response.success) {
                    console.log("Image supprimé avec succès");
                    imageTarget.parentElement.remove();
                } else {
                    // Gérer les erreurs de manière appropriée
                    console.error('Erreur lors de la suppression de l\'image : ' + response.error);
                }
            },
            //*/
            error: function (xhr, status, error) {
                // Gérer les erreurs de manière appropriée
                console.error('Erreur lors de la requête AJAX : ' + error);
            }
        });
    }
}
