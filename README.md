# Documentation

This is the documentation of our service. Here you will find information about the available endpoints,
how to use them, and what to expect in the responses.

## Postman endpoints

You wil find the postman endpoints at /postman/ranking_points.postman_collection
On postman app click import and select or drag and drop the previous file.

## Docker configuration

Run on your bash after clone the repository:

```
    cd ranking-points
    docker compose up -d --build
    docker compose exec app composer install
    docker compose exec app php ./vendor/bin/phpunit tests (to run all tests)
```

## Available Endpoints

### 1. Get all users

GET /user/get_all

#### Description

This endpoint returns a list of all users sorted by scores.

#### Query parameters

- None

#### Successful response

```json
{
    "status_code": 200,
    "message": "Users found successfully",
    "result": [
        {
            "id": "a user id",
            "score": 0
        },
        ...
    ]
}

```

### 2. Get topN ranked users

GET /ranking?type=TopN

#### Description

This endpoint returns a list of top N users with highest scores.

#### Query parameters

- type: string

#### Successful response

```json

{
    "status_code": 200,
    "message": "Users found successfully",
    "result": [
        {
            "id": "first user id",
            "score": 10
        },
        {
            "id": "second user id",
            "score": 5
        },
        ...
    ]
}

```

### 3. Get atN/M ranked users

GET /ranking?type=AtN/M

#### Description

This endpoint returns a list of M users with higher score than the user at N position,
the user at the N position and the M users with lower scores than the user at position N.

#### Query parameters

- type: string

#### Successful response

```json

{
    "status_code": 200,
    "message": "Users found successfully",
    "result": [
        {
            "id": "user id",
            "score": 10
        },
        {
            "id": "user id at position N",
            "score": 5
        },
        {
            "id": "user id",
            "score": 3
        },
        ...
    ]
}

```

### 4. Add user score

GET /user/{user_id}/score

#### Description

This endpoint adds a given score to the given user.
In case the given user does not exist in the system, the service creates it and adds the given score.
Otherwise, the user's score is updated based on whether an absolute or relative score is given.

#### Body parameters

Absolute score

- total: int

```json
{
  "total": 200
}
```

Relative score

- score: string

```json
{
  "score": "+30"
}
```

#### Successful response

```json
{
  "status_code": 200,
  "message": "The user's score with id {user_id} has been successfully saved",
  "result": 1
}
```
