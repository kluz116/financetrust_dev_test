package com.financetrust.test.entities;

import lombok.AllArgsConstructor;
import lombok.Data;

/**
 * @author Muyinda Rogers
 * @Date 2024-06-27
 * @Email moverr@gmail.com
 */

@Data
public class DeliveryData {
    private String postcode;
    private String recipe;
    private String delivery;

    public DeliveryData() {
    }

    public DeliveryData(String postcode, String recipe, String delivery) {
        this.postcode = postcode;
        this.recipe = recipe;
        this.delivery = delivery;
    }
}
