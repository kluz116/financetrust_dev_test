package com.financetrust.test.controllers;

import com.financetrust.test.responses.BusiestPostcodeResponse;
import com.financetrust.test.responses.CountPerRecipeResponse;
import com.financetrust.test.responses.MatchByNameRecipeResponse;
import com.financetrust.test.responses.UniqueRecipeCountResponse;
import com.financetrust.test.services.DeliveryDataService;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.http.HttpStatus;
import org.springframework.http.ResponseEntity;
import org.springframework.validation.annotation.Validated;
import org.springframework.web.bind.annotation.*;
import org.springframework.web.multipart.MultipartFile;

import java.io.IOException;
import java.io.InputStream;
import java.util.List;

/**
 * @author Muyinda Rogers
 * @Date 2024-06-27
 * @Email moverr@gmail.com
 */
@RestController
@RequestMapping("/")
@Validated
public class DeliveryDataController {


    @Autowired
    private DeliveryDataService service;



    @PostMapping("/upload-fixture")
    public ResponseEntity<String> uploadFixture(@RequestParam("file") MultipartFile file) {
        if (file.isEmpty()) {
            return ResponseEntity.badRequest().body("Please upload a file");
        }

        try {
            InputStream inputStream = file.getInputStream();
            service.save(inputStream);
            return ResponseEntity.status(HttpStatus.CREATED).body("File uploaded successfully");
        } catch (IOException e) {
            return ResponseEntity.status(HttpStatus.INTERNAL_SERVER_ERROR).body("Failed to upload file");
        }
    }

    @GetMapping(value = "unique-recipe-count")
    public ResponseEntity<UniqueRecipeCountResponse> UniqueRecipeCount(){
        try {
            return  ResponseEntity.ok(service.getUniqueRecipeCount());
        }
        catch (IOException ex){
            throw new RuntimeException("Error reading delivery data", ex);

        }
    }

    @GetMapping(value = "count_per_recipe")
    public ResponseEntity<CountPerRecipeResponse> countPerRecipe(){
        try {
            return  ResponseEntity.ok(service.getCountPerRecipe());
        }
        catch (IOException ex){
            throw new RuntimeException("Error reading delivery data", ex);

        }
    }

    @GetMapping(value = "busiest_postcode")
    public ResponseEntity<BusiestPostcodeResponse> busiestPostcode(){

        try {
            return  ResponseEntity.ok(service.getBusiestPostcode());
        }
        catch (IOException ex){
            throw new RuntimeException("Error reading delivery data", ex);

        }

    }



    @GetMapping(value = "match_by_name")
    public MatchByNameRecipeResponse matchByName(@RequestParam("keywords") List<String> keywords){
        try {
            return ResponseEntity.ok(service.GetMatchByName(keywords)).getBody();
        }
        catch (IOException ex){
            throw new RuntimeException("Error reading delivery data", ex);

        }

    }



}
