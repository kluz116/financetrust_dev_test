package com.financetrust.test.controllers;

import com.financetrust.test.responses.BusiestPostcodeResponse;
import com.financetrust.test.responses.CountPerRecipeResponse;
import com.financetrust.test.responses.MatchByNameRecipeResponse;
import com.financetrust.test.responses.UniqueRecipeCountResponse;
import com.financetrust.test.services.DeliveryDataService;
import org.junit.jupiter.api.AfterEach;
import org.junit.jupiter.api.BeforeEach;
import org.junit.jupiter.api.Test;
import org.mockito.InjectMocks;
import org.mockito.MockitoAnnotations;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.boot.test.autoconfigure.web.servlet.AutoConfigureMockMvc;
import org.springframework.boot.test.context.SpringBootTest;
import org.springframework.boot.test.mock.mockito.MockBean;
import org.springframework.mock.web.MockMultipartFile;
import org.springframework.test.web.servlet.MockMvc;
import org.springframework.test.web.servlet.setup.MockMvcBuilders;

import java.io.InputStream;
import java.util.ArrayList;
import java.util.List;

import static org.mockito.ArgumentMatchers.any;
import static org.mockito.Mockito.*;
import static org.springframework.test.web.servlet.request.MockMvcRequestBuilders.get;
import static org.springframework.test.web.servlet.request.MockMvcRequestBuilders.multipart;
import static org.springframework.test.web.servlet.result.MockMvcResultMatchers.jsonPath;
import static org.springframework.test.web.servlet.result.MockMvcResultMatchers.status;

/**
 * @author Muyinda Rogers
 * @Date 2024-06-27
 * @Email moverr@gmail.com
 */
@SpringBootTest
@AutoConfigureMockMvc
class DeliveryDataControllerTest {

    @BeforeEach
    void setUp() {
    }

    @AfterEach
    void tearDown() {
    }

    @Test
    void uploadFixture() {
    }

    @Test
    void uniqueRecipeCount() {
    }

    @Test
    void countPerRecipe() {
    }

    @Test
    void busiestPostcode() {
    }

    @Test
    void matchByName() {
    }


    @Autowired
    private MockMvc mockMvc;

    @MockBean
    private DeliveryDataService deliveryDataService;

    @InjectMocks
    private DeliveryDataController deliveryDataController;

    @BeforeEach
    public void setup() {
        MockitoAnnotations.openMocks(this);
        mockMvc = MockMvcBuilders.standaloneSetup(deliveryDataController).build();
    }

    @Test
    public void testUploadFixture() throws Exception {
        MockMultipartFile mockFile = new MockMultipartFile("file", "test.json", "application/json", "{\"key\":\"value\"}".getBytes());

        mockMvc.perform(multipart("/upload-fixture")
                        .file(mockFile))
                .andExpect(status().isCreated());

        verify(deliveryDataService, times(1)).save(any(InputStream.class));
    }

    @Test
    public void testUploadFixtureEmptyFile() throws Exception {
        MockMultipartFile emptyFile = new MockMultipartFile("file", "", "application/json", new byte[0]);

        mockMvc.perform(multipart("/upload-fixture")
                        .file(emptyFile))
                .andExpect(status().isBadRequest());
    }

    @Test
    public void testGetUniqueRecipeCount() throws Exception {
        UniqueRecipeCountResponse response = new UniqueRecipeCountResponse(5);
        when(deliveryDataService.getUniqueRecipeCount()).thenReturn(response);

        mockMvc.perform(get("/unique-recipe-count"))
                .andExpect(status().isOk())
                .andExpect(jsonPath("$.unique_recipe_count").value(5));

        verify(deliveryDataService, times(1)).getUniqueRecipeCount();
    }

    @Test
    public void testGetCountPerRecipe() throws Exception {
        CountPerRecipeResponse response = new CountPerRecipeResponse(null);
        when(deliveryDataService.getCountPerRecipe()).thenReturn(response);

        mockMvc.perform(get("/count_per_recipe"))
                .andExpect(status().isOk());

        verify(deliveryDataService, times(1)).getCountPerRecipe();
    }

    @Test
    public void testGetBusiestPostcode() throws Exception {
        BusiestPostcodeResponse response = new BusiestPostcodeResponse("10120", 1000);
        when(deliveryDataService.getBusiestPostcode()).thenReturn(response);

        mockMvc.perform(get("/busiest_postcode"))
                .andExpect(status().isOk())
                .andExpect(jsonPath("$.postcode").value("10120"))
                .andExpect(jsonPath("$.delivery_count").value(1000));

        verify(deliveryDataService, times(1)).getBusiestPostcode();
    }

    @Test
    public void testMatchByName() throws Exception {
        List<String> matchedRecipes = new ArrayList<>();
        matchedRecipes.add("Potato");
        matchedRecipes.add("Veggie");
        matchedRecipes.add("Mushroom");
        MatchByNameRecipeResponse response = new MatchByNameRecipeResponse(matchedRecipes);
        when(deliveryDataService.GetMatchByName(anyList())).thenReturn(response);

        mockMvc.perform(get("/match_by_name")
                        .param("keywords", "Potato", "Veggie", "Mushroom"))
                .andExpect(status().isOk());

        verify(deliveryDataService, times(1)).GetMatchByName(anyList());
    }

}