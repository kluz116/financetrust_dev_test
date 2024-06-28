package com.financetrust.test.responses;

import com.fasterxml.jackson.annotation.JsonProperty;
import lombok.AllArgsConstructor;
import lombok.Data;

/**
 * @author Muyinda Rogers
 * @Date 2024-06-27
 * @Email moverr@gmail.com
 */
@AllArgsConstructor
@Data
public class UniqueRecipeCountResponse {
    @JsonProperty("unique_recipe_count")
    private int uniqueRecipeCount;
}
