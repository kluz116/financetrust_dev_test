package com.financetrust.test.services;

import com.fasterxml.jackson.core.type.TypeReference;
import com.fasterxml.jackson.databind.ObjectMapper;
import com.financetrust.test.entities.DeliveryData;
import com.financetrust.test.responses.*;
import org.springframework.beans.factory.annotation.Value;
import org.springframework.core.io.Resource;
import org.springframework.stereotype.Service;
import org.springframework.util.Assert;
import org.springframework.util.FileCopyUtils;

import java.io.IOException;
import java.io.InputStream;
import java.util.List;
import java.util.Map;
import java.util.stream.Collectors;

/**
 * @author Muyinda Rogers
 * @Date 2024-06-27
 * @Email moverr@gmail.com
 */
@Service
public class DeliveryDataService {

    @Value("classpath:data.json")
    private Resource resource;
    private final ObjectMapper objectMapper;
    private List<DeliveryData> deliveryDataList;

    public DeliveryDataService() {
        objectMapper = new ObjectMapper();

    }



    /*
    This only updates the current data list in memory
     */
    public void  save(InputStream inputStream) throws IOException {
        Assert.notNull(inputStream, "Input stream cannot be null");
        byte[] bytes = FileCopyUtils.copyToByteArray(inputStream);
       deliveryDataList = objectMapper.readValue(bytes, new TypeReference<>() {
       });

    }



    public UniqueRecipeCountResponse getUniqueRecipeCount() throws IOException {
        List<DeliveryData> deliveryDataLst = getDeliveryData();
        int uniqueRecipeCount = deliveryDataLst.stream().map(x->x.getRecipe().trim())
                .distinct()
                .toList()
                .size();
        return new UniqueRecipeCountResponse(uniqueRecipeCount);
    }

    public CountPerRecipeResponse getCountPerRecipe() throws IOException {
        List<DeliveryData> deliveryDataLst = getDeliveryData();

        Map<String, Long> recipeCounts = deliveryDataLst.stream()
                .collect(Collectors.groupingBy(DeliveryData::getRecipe, Collectors.counting()));

        List<CountPerRecipe> countPerRecipe = recipeCounts.entrySet().stream()
                .map(entry -> new CountPerRecipe(entry.getKey(), entry.getValue().intValue()))
                .toList();

        return  new CountPerRecipeResponse(countPerRecipe);
    }

    public BusiestPostcodeResponse getBusiestPostcode() throws IOException {

        List<DeliveryData> deliveryDataLst = getDeliveryData();
        Map<String, Long> postcodeCounts = deliveryDataLst.stream()
                .collect(Collectors.groupingBy(DeliveryData::getPostcode, Collectors.counting()));

        Map.Entry<String, Long> busiestEntry = postcodeCounts.entrySet().stream()
                .max(Map.Entry.comparingByValue())
                .orElseThrow(() -> new RuntimeException("No data found"));

        return new BusiestPostcodeResponse(busiestEntry.getKey(), busiestEntry.getValue().intValue());

    }

   
    
    public MatchByNameRecipeResponse GetMatchByName(List<String> keywords) throws IOException {
        List<DeliveryData> deliveryDataLst = getDeliveryData();
        List<String> matchingRecipes = deliveryDataLst.stream()
                .map(DeliveryData::getRecipe)
                .filter(recipe -> keywords.stream().anyMatch(recipe::contains))
                .distinct()
                .sorted()
                .toList();

        return  new MatchByNameRecipeResponse(matchingRecipes);

    }




    protected List<DeliveryData> getDeliveryData() throws IOException {
        if (deliveryDataList == null)
            deliveryDataList = objectMapper.readValue(resource.getInputStream(), new TypeReference<List<DeliveryData>>() {
            });

        return deliveryDataList;

    }


}
