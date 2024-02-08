# API documentation

Every request has to be authenticated via token.
Example of authenticated call in curl
```sh
curl -X GET \
    -H 'Accept: application/json' \
    -H 'Authorization: token YOUR_TOKEN' \
    -iLk \
    https://localhost/event/01234567-f39e-7a77-908e-c7be3cab6409
```

Any endpoint can return: HTTP 401 - authentication error

## Service
Service is what somebody provides. Example of service include:
- language lesson,
- hair cutting.

### Create
**URL**: `/services`  
**Method:** `POST`  
**Data**

| key                | type             | description                                       |
|--------------------|------------------|---------------------------------------------------|
| cancellation_limit | positive integer | how many minutes before the start can be canceled |
| capacity           | positive integer | available slots                                   |
| description        | text             |                                                   |
| duration           | positive integer | minutes                                           |
| name               | text             |                                                   |

**Statuses**

| result       | HTTP status |
|--------------|-------------|
| success      | 201         |
| bad data     | 400         |
| invalid data | 400         |
| missing data | 400         |

### Get
**URL**: `/service/{ID}`, ID is a UUID v7  
**Method:** `GET`  
**Statuses**

| result        | HTTP status  |
|---------------|--------------|
| success       | 200          |
| wrong service | 404          |

- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - 

## Event
Event is a specific time-slot of a service. Examples:
- language lesson next Monday between 08:00-10:00,
- hair cutting today from 18:30-19:00.

### Create
**URL**: `/events`  
**Method:** `POST`  
**Data**

| key        | type             | description                      |
|------------|------------------|----------------------------------|
| date       | date             | format YYYY-MM-DD                |
| end        | positive integer | number of minutes since midnight |
| service_id | text             | UUID v7 format                   |
| start      | positive integer | number of minutes since midnight |

**Statuses**
- success - 201
- bad data - 400
- invalid data - 400
- missing data - 400
- referencing service not owned by me - 404

### Get
**URL**: `/event/{ID}`, ID is a UUID v7  
**Method:** `GET`  
**Statuses**

| result        | HTTP status |
|---------------|-------------|
| success       | 200         |
| wrong service | 404         |

### Delete
**URL**: `/event/{ID}`, ID is a UUID v7  
**Method:** `DELETE`  
**Statuses**

| result      | HTTP status |
|-------------|-------------|
| success     | 204         |
| wrong event | 404         |

- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - 

## Reservation
Reservation is a slot in event. Example:
- Alice attends language lesson next Monday at 8PM,
- Bob attends language lesson next Monday at 8PM,
- 3 more slots are empty to book.

### Create
**URL**: `/reservations`  
**Method:** `POST`  
**Data**

When booking as registered user

| key         | type   | description    |
|-------------|--------|----------------|
| description | text   |                |
| event_id    | string | UUID v7 format |

When booking as service owner

| key         | type   | description    |
|-------------|--------|----------------|
| description | text   |                |
| event_id    | string | UUID v7 format |
| user_email  | text   |                |
| user_name   | text   |                |

**Statuses**

| result                              | HTTP status |
|-------------------------------------|-------------|
| success                             | 201         |
| bad data                            | 400         |
| invalid data                        | 400         |
| missing data                        | 400         |
| referencing service not owned by me | 404         |
| already full capacity               | 409         |
| booking to canceled event           | 409         |

### Get
**URL**: `/reservation/{ID}`, ID is a UUID v7  
**Method:** `GET`  
**Statuses**

| result    | HTTP status |
|-----------|-------------|
| success   | 200         |

### Delete
**URL**: `/reservation/{ID}`, ID is a UUID v7  
**Method:** `DELETE`  
**Statuses**

| result            | HTTP status |
|-------------------|-------------|
| success           | 204         |
| wrong reservation | 404         |
