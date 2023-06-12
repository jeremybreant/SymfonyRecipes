// assets/controllers/form-collection_controller.js

import { Controller } from '@hotwired/stimulus';

export default class extends Controller {

    static values = {
        recipe :Object,
        requestedfoodquantity   : Number,
    }

    incrementFoodQuantity(event)
    {
        this.requestedfoodquantityValue++;
        this.changeQuantityDisplay();

        this.recipeValue.recipeIngredients.forEach(
            recipeIngredient => this.changeIngredientQuantityDisplay(recipeIngredient)
        );
    }

    decrementFoodQuantity(event)
    {
        if(this.requestedfoodquantityValue === 1) return;
        this.requestedfoodquantityValue--;
        this.changeQuantityDisplay();
        this.recipeValue.recipeIngredients.forEach(
            recipeIngredient => this.changeIngredientQuantityDisplay(recipeIngredient)
        );
    }

    changeQuantityDisplay()
    {
        let foodQuantityNum = document.getElementById("foodQuantityNum");
        foodQuantityNum.innerHTML = this.requestedfoodquantityValue;
    }

    changeIngredientQuantityDisplay(recipeIngredient)
    {
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
        if(dividedQuantity === 0.10 || dividedQuantity === 0.30 || dividedQuantity === 0.40 || dividedQuantity === 0.60 || dividedQuantity === 0.70 || dividedQuantity === 0.90)
        {
            return dividedQuantity*10+"/10";
        }
        if(dividedQuantity === 0.20 || dividedQuantity === 0.40 || dividedQuantity === 0.80)
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