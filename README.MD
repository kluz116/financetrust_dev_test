# Small Stats API

In the given assignment we suggest you to process an automatically generated JSON file with some data and calculated some stats. Please ensure that you read, understand and follow instructions.

## Instructions

1. Folk the repository.
2. Clone.
3. Create a new branch called `<name>_ftb_dev` eg allan_musembya_ftb_dev.
4. Create a pull request from your `ftb_dev` branch to the master branch.
5. Reply to the thread when  you're done so we should start reviewing your code

## Given

Json data found in this repo.

## Functional Requirements

1. Count the number of unique recipe names.
2. Count the number of occurences for each unique recipe name (alphabetically ordered by recipe name).
3. Find the postcode with most delivered recipes.
4. List the recipe names (alphabetically ordered) that contain in their name one of the following words:
   - Potato
   - Veggie
   - Mushroom

## Non-functional Requirements

1. The application is packaged with [Docker] (important)
2. Setup scripts are provided. (Important)
3. The submission is provided web `API`. Functional requirements are presented querable endpoints. (Important)
4. It should be possible to (implementation is up to you):  
   a. provide a custom fixtures file as input  
   b. provide custom recipe names to search by (functional reqs. 4)

## Expected output

Generate a JSON file of the following format:

1.

```json5
{
  unique_recipe_count: 15,
}
```

2.

```json5
{
  count_per_recipe: [
    {
      recipe: "Mediterranean Baked Veggies",
      count: 1,
    },
    {
      recipe: "Speedy Steak Fajitas",
      count: 1,
    },
    {
      recipe: "Tex-Mex Tilapia",
      count: 3,
    },
  ],
}
```

3.

```json5
    "busiest_postcode": {
        "postcode": "10120",
        "delivery_count": 1000
    }
```

4.

```json5
    "match_by_name": [
        "Mediterranean Baked Veggies", "Speedy Steak Fajitas", "Tex-Mex Tilapia"
    ]
}
```

## Review Criteria

We expect that the assignment will not take more than 2-3 hours of work. In our judgement we rely on common sense and do not expect production ready code. We are rather interested in your problem solving skills and command of the programming language that you chose.

It worth mentioning that we will be testing your submission against different input data sets.

**General criteria from most important to less important**:

1. Functional and non-functional requirements are met.
2. Prefer application efficiency over code organization complexity.
3. Code is readable and comprehensible. Setup instructions and run instructions are provided.
4. Tests showcased are a plus (_no need to cover everything_).
5. Supporting notes on taken decisions and further clarifications are welcome.
