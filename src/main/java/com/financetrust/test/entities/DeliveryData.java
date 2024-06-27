package com.financetrust.test.entities;

import lombok.AllArgsConstructor;
import lombok.Data;

/**
 * @author Muyinda Rogers
 * @Date 2024-06-27
 * @Email moverr@gmail.com
 */

@Data
@AllArgsConstructor
public class DeliveryData {
    private String postcode;
    private String recipe;
    private String delivery;
}
