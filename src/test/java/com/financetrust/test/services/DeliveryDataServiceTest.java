package com.financetrust.test.services;

import com.fasterxml.jackson.core.type.TypeReference;
import com.fasterxml.jackson.databind.ObjectMapper;
import com.financetrust.test.entities.DeliveryData;
import com.financetrust.test.responses.BusiestPostcodeResponse;
import com.financetrust.test.responses.CountPerRecipeResponse;
import com.financetrust.test.responses.MatchByNameRecipeResponse;
import com.financetrust.test.responses.UniqueRecipeCountResponse;
import org.junit.jupiter.api.BeforeEach;
import org.junit.jupiter.api.Test;
import org.mockito.*;
import org.springframework.boot.test.autoconfigure.web.servlet.AutoConfigureMockMvc;
import org.springframework.boot.test.context.SpringBootTest;
import org.springframework.core.io.Resource;

import java.io.IOException;
import java.io.InputStream;
import java.util.Arrays;
import java.util.List;

import static org.junit.jupiter.api.Assertions.assertEquals;
import static org.junit.jupiter.api.Assertions.assertTrue;
import static org.mockito.ArgumentMatchers.any;
import static org.mockito.Mockito.mock;


/**
 * @author Muyinda Rogers
 * @Date 2024-06-27
 * @Email moverr@gmail.com
 */
@SpringBootTest
@AutoConfigureMockMvc
class DeliveryDataServiceTest {


    @InjectMocks
    @Spy
    private DeliveryDataService deliveryDataService;

    @Mock
    private Resource resource;

    @Mock
    private ObjectMapper objectMapper;

    private List<DeliveryData> deliveryDataList;

    @BeforeEach
    public void setUp() throws IOException {
        MockitoAnnotations.openMocks(this);

        DeliveryData data1 = new DeliveryData("10224", "Creamy Dill Chicken","Wednesday 1AM - 7PM");
        DeliveryData data2 = new DeliveryData("10208", "Speedy Steak Fajitas", "Thursday 7AM - 5PM");
        DeliveryData data3 = new DeliveryData("10120",  "Cherry Balsamic Pork Chops", "Thursday 7AM - 9PM");
        DeliveryData data4 = new DeliveryData("10186",  "Cherry Balsamic Pork Chops", "Saturday 1AM - 8PM");

        deliveryDataList = Arrays.asList(data1, data2, data3, data4);

        Mockito.when(objectMapper.readValue(any(InputStream.class), any(TypeReference.class)))
                .thenReturn(deliveryDataList);
        Mockito.when(resource.getInputStream()).thenReturn(mock(InputStream.class));

        // Spy on getDeliveryData to return the predefined list
        Mockito.doReturn(deliveryDataList).when(deliveryDataService).getDeliveryData();
    }



    @Test
    public void testGetUniqueRecipeCount() throws IOException {
        UniqueRecipeCountResponse response = deliveryDataService.getUniqueRecipeCount();
        assertEquals(3, response.getUniqueRecipeCount());
    }

    @Test
    public void testGetCountPerRecipe() throws IOException {
        CountPerRecipeResponse response = deliveryDataService.getCountPerRecipe();
        assertEquals(3, response.getRecipeList().size());
        assertTrue(response.getRecipeList().stream().anyMatch(recipe -> recipe.getRecipe().equals("Cherry Balsamic Pork Chops")));
    }

    @Test
    public void testGetBusiestPostcode() throws IOException {
        BusiestPostcodeResponse response = deliveryDataService.getBusiestPostcode();
        assertEquals("10208", response.getPostcode());
        assertEquals(1, response.getDeliveryCount());
    }

    @Test
    public void testGetMatchByName() throws IOException {
        List<String> keywords = Arrays.asList("Creamy", "Speedy", "Cherry");
        MatchByNameRecipeResponse response = deliveryDataService.GetMatchByName(keywords);
        assertTrue(response.getMatchedRecipes().contains("Creamy Dill Chicken"));
    }


}

