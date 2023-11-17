// assets/controllers/form-collection_controller.js

/**
 * This stimulus is used to update the amount of ingredient depending of the required food quantity
 * eg. A recipe for 4 persons can be changed to display ingredient for only 2 persons
 */

import { Controller } from '@hotwired/stimulus';

export default class extends Controller {

    static values = {
        recipe :Object,
        requestedfoodquantity :Number,
    }

    incrementFoodQuantity(event)
    {
        this.requestedfoodquantityValue++;
        this.changeRequiredQuantityDisplayed();

        this.changeAllIngredientQuantityDisplayed();
    }

    decrementFoodQuantity(event)
    {
        if(this.requestedfoodquantityValue === 1) return;
        this.requestedfoodquantityValue--;
        this.changeRequiredQuantityDisplayed();

        this.changeAllIngredientQuantityDisplayed();
    }

    changeAllIngredientQuantityDisplayed(){
        this.recipeValue.recipeIngredients.forEach(
            recipeIngredient => this.changeIngredientQuantityDisplayed(recipeIngredient)
        );
    }

    changeRequiredQuantityDisplayed()
    {
        let foodQuantityNum = document.getElementById("foodQuantityNum");
        foodQuantityNum.innerHTML = this.requestedfoodquantityValue;
    }

    changeIngredientQuantityDisplayed(recipeIngredient)
    {
        // No quantity, no need to update it
        if(recipeIngredient.quantity === null){
            return;
        }

        let dividedQuantity;
        let targetNode = document.getElementById(recipeIngredient.id);

        dividedQuantity = recipeIngredient.quantity * (this.requestedfoodquantityValue / this.recipeValue.foodQuantity);

        targetNode.innerHTML = this.giveDisplayableValue(dividedQuantity)
    }

    giveDisplayableValue(dividedQuantity)
    {
        dividedQuantity = Math.round(dividedQuantity*10)/10;
        if(dividedQuantity >= 1)
        {
            return  dividedQuantity;
        }
        if(dividedQuantity < 0.10)
        {
            return "";
        }
        if(dividedQuantity === 0.10 || dividedQuantity === 0.30 || dividedQuantity === 0.70 || dividedQuantity === 0.90)
        {
            return dividedQuantity*10+"/10";
        }
        if(dividedQuantity === 0.20 || dividedQuantity === 0.40 || dividedQuantity === 0.60 || dividedQuantity === 0.80)
        {
            return dividedQuantity*5+"/5";
        }
        if(dividedQuantity === 0.25 || dividedQuantity === 0.75)
        {
            return dividedQuantity*4+"/4";
        }
        if(dividedQuantity >= 0.32 && dividedQuantity <= 0.34 || dividedQuantity >= 0.65 && dividedQuantity <= 0.67 )
        {
            return Math.round(dividedQuantity*3)+"/3";
        }
        if(dividedQuantity === 0.5)
        {
            return "1/2";
        }
        return dividedQuantity;

    }
}