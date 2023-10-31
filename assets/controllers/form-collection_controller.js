// assets/controllers/form-collection_controller.js
/**
 * This stimuluse is used to add recipeIngredient form in the recipe form
 * It allow to add or remove ingredient to recipe via form 
 */

import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ["collectionContainer"]

    static values = {
        index    : Number,
        prototype: String,
    }

    addCollectionElement(event)
    {
        const item = document.createElement('div');
        item.innerHTML = this.prototypeValue.replace(/__name__/g, this.indexValue);
        this.collectionContainerTarget.appendChild(item);
        this.indexValue++;
    }

    removeCollectionElement(event)
    {
        event.explicitOriginalTarget.closest('.recipeIngredientRow').remove();
    }
}