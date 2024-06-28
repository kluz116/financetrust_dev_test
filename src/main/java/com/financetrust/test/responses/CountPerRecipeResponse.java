package com.financetrust.test.responses;

import com.fasterxml.jackson.annotation.JsonProperty;
import lombok.AllArgsConstructor;
import lombok.Data;

import java.util.List;

/**
 * @author Muyinda Rogers
 * @Date 2024-06-27
 * @Email moverr@gmail.com
 */
@Data
@AllArgsConstructor
public class CountPerRecipeResponse {
    @JsonProperty("count_per_recipe")
    private List<CountPerRecipe> recipeList;
}
