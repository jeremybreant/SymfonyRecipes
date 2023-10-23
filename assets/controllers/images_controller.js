// assets/controllers/images_controller.js

import { Controller } from '@hotwired/stimulus';

export default class extends Controller {

    static values = {
        imageid: Number,
    }
    static targets = ["image"];

    deleteImages() {
        if (confirm('Etes-vous sûre de vouloir supprimer cette image ? La suppression sera immédiate et définitive.')) {
            console.log(this.imageidValue);
            let imageTarget = this.imageTarget;
            $.ajax({
                url: '/image/suppression',
                method: 'DELETE',
                data: {
                    imageId: this.imageidValue,
                },
                //*
                success: function (response) {
                    // Mettre à jour l'apparence du bouton en fonction de la réponse
                    if (response.success) {
                        console.log("Image supprimé avec succès");
                        imageTarget.remove();
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
}
