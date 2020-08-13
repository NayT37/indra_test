# Indra Test

Download repository and run `composer install`.

Create `settings.php` and `settings.local.php` in order to make it work.

SQL dump it's inside the code. Connect DB.

## GET ALL
Use `~/api/user` to return all users.

## GET SINGLE
Use `~/api/user/<ID Document>` to return specific user by ID document (CC).

## POST

### Using postman:
1) Generate a session token. For this, go to  `~/session/token` and add it to the header as a `X-CSRF-Token`.

2) Authenticate an user with user and password (user: root, password: root, it's suggested) and add it to the header

3) Add `Content-Type: application/json` and `Accept: application/json` to the headers.

4) Finally, add a body with the following structure:
```
{
    "title": [
        {
            "value": "Test 4"
        }
    ],
    "field_name": [
        {
            "value": "Test Name 4"
        }
    ],
    "field_last_name": [
        {
            "value": "Last Name 4"
        }
    ],
    "field_id_doc": [
        {
            "value": "55447788"
        }
    ],
    "field_email": [
        {
            "value": "test3@test.com"
        }
    ],
    "field_phone": [
        {
            "value": "77445522"
        }
    ],
    "field_birthdate": [
        {
            "value": "1999-05-28"
        }
    ],
    "type": [
        {
            "target_id": "user"
        }
    ]
}
```

### Using cURL

```
curl --location --request POST 'indra.local/entity/node?_format=json' \
--header 'Content-Type: application/json' \
--header 'Accept: application/json' \
--header 'X-CSRF-Token: lPeI7UQhJjfKK_zX95fBuChTK5icg5vC54UfuInjYEA' \
--header 'Authorization: Basic cm9vdDpyb290' \
--data-raw '{
    "title": [
        {
            "value": "Test 4"
        }
    ],
    "field_name": [
        {
            "value": "Test Name 4"
        }
    ],
    "field_last_name": [
        {
            "value": "Last Name 4"
        }
    ],
    "field_id_doc": [
        {
            "value": "55447788"
        }
    ],
    "field_email": [
        {
            "value": "test3@test.com"
        }
    ],
    "field_phone": [
        {
            "value": "77445522"
        }
    ],
    "field_birthdate": [
        {
            "value": "1999-05-28"
        }
    ],
    "type": [
        {
            "target_id": "user"
        }
    ]
}'
```
