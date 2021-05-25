# Product-stock management

-   Manage products with code, name and description fields
-   Add stock related to products with on_hand, taken and production_date fields

## API routes

### Register

-   POST [http://test.local/api/register](#)

#### Sample response

```
{
    "success": true,
    "data": {
        "user": {
            "name": "Ankit",
            "email": "ankit@patel.com",
            "updated_at": "2021-05-24T02:14:37.000000Z",
            "created_at": "2021-05-24T02:14:37.000000Z",
            "id": 1
        },
        "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIxIiwianRpIjoiMTk1NmI1OGZjMzU2NWU4YzBiOTE4MjYzZjdjN2NjYmYyMzQ3MWM0Y2U3NTMzM2ZiZmFjMzgxMzE5NDllMmIxOWY0NGY0NDVmOT
gwOGQ0MzYiLCJpYXQiOjE2MjE5MDg4NzcsIm5iZiI6MTYyMTkwODg3NywiZXhwIjoxNjUzNDQ0ODc3LCJzdWIiOiIxIiwic2NvcGVzIjpbXX0.eQFqyHyGZmatV5NmmTmOdGUFW1GhFckqW9oYGPJREQMoB4XdP1lTfsUmTi-4ercrwVpU
HygUdCm6W9o9KlH3mn8-ipZCOSfhIKhEB77dsTg0wnfGKuCFp-G3FHB8_E6CY02WVyHjbUNx_4BQYv_-LC72godvprzCGbaj3RSiJ3-kn0Lv0zYeeTb9atDQ1GSTRdlxSLE-R2cJZgNxnhVGqUSCZavNpWjUc3-UDELnLvjPMBCrJ7LNAa
4xfCbD_NTvpbQXtD-vEEdXh2UKR3IKvMQkpT-txstG_9htsH6tOt2Qa8GiCuP2DElu2gloTFKk5mxG571Eq6JS_1BxrFQcp8bunYUtB9oPQvAUoKcCWu9u1RARYRpAaVaGnZ4c1iPsm6tQTmxXoQrTu5do8eJ_mo6Km28Hsi4UPLR1lmhT
pbrkxJFPsaY9BZw8Fca9FZfUiGFkr9-FweyY_2QUzOdDz3mu5CT6H0H2pzlOJjIfC5x6rHJBz6jIO5BDA4OknXu5ZTVHWc-IqDe7K0QrIRYJv7tdoa3uTk1jSYEnvZA6l9IS6mNdRko1DLmbN5zpv5shua2JG2WKvZi4fVsFxbzhsx1Wt9
Wnaq1SvG100ivKaIdM3XPprH8saKVbAN0ddkr35dZLyOUrA-wH7_fFfbKx8WcUkweKkWREa_qsImM2ojs"
    },
    "message": "User registered successfully."
}
```

---

### Login

-   POST [http://test.local/api/login](#)

#### Sample response

```
{
    "success": true,
    "data": {
        "user": {
            "id": 1,
            "name": "Ankit",
            "email": "ankit@patel.com",
            "email_verified_at": null,
            "created_at": "2021-05-24T02:14:37.000000Z",
            "updated_at": "2021-05-24T02:14:37.000000Z"
        },
        "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIxIiwianRpIjoiMTk1NmI1OGZjMzU2NWU4YzBiOTE4MjYzZjdjN2NjYmYyMzQ3MWM0Y2U3NTMzM2ZiZmFjMzgxMzE5NDllMmIxOWY0NGY0NDVmOT
gwOGQ0MzYiLCJpYXQiOjE2MjE5MDg4NzcsIm5iZiI6MTYyMTkwODg3NywiZXhwIjoxNjUzNDQ0ODc3LCJzdWIiOiIxIiwic2NvcGVzIjpbXX0.eQFqyHyGZmatV5NmmTmOdGUFW1GhFckqW9oYGPJREQMoB4XdP1lTfsUmTi-4ercrwVpU
HygUdCm6W9o9KlH3mn8-ipZCOSfhIKhEB77dsTg0wnfGKuCFp-G3FHB8_E6CY02WVyHjbUNx_4BQYv_-LC72godvprzCGbaj3RSiJ3-kn0Lv0zYeeTb9atDQ1GSTRdlxSLE-R2cJZgNxnhVGqUSCZavNpWjUc3-UDELnLvjPMBCrJ7LNAa
4xfCbD_NTvpbQXtD-vEEdXh2UKR3IKvMQkpT-txstG_9htsH6tOt2Qa8GiCuP2DElu2gloTFKk5mxG571Eq6JS_1BxrFQcp8bunYUtB9oPQvAUoKcCWu9u1RARYRpAaVaGnZ4c1iPsm6tQTmxXoQrTu5do8eJ_mo6Km28Hsi4UPLR1lmhT
pbrkxJFPsaY9BZw8Fca9FZfUiGFkr9-FweyY_2QUzOdDz3mu5CT6H0H2pzlOJjIfC5x6rHJBz6jIO5BDA4OknXu5ZTVHWc-IqDe7K0QrIRYJv7tdoa3uTk1jSYEnvZA6l9IS6mNdRko1DLmbN5zpv5shua2JG2WKvZi4fVsFxbzhsx1Wt9
Wnaq1SvG100ivKaIdM3XPprH8saKVbAN0ddkr35dZLyOUrA-wH7_fFfbKx8WcUkweKkWREa_qsImM2ojs"
    },
    "message": "User loggedin successfully."
}
```

---

### Add, Update, Delete product

-   POST [http://test.local/api/products](#)

#### Sample response

```
{
    "success": true,
    "data": {
        "product": {
            "code": "1234",
            "name": "name",
            "description": "lorem ipsum description",
            "updated_at": "2021-05-24T02:42:07.000000Z",
            "created_at": "2021-05-24T02:42:07.000000Z",
            "id": 3341
        }
    },
    "message": "Product Added successfully."
}
```

-   PATCH [http://test.local/api/products/{id}](#)

#### Sample response

```
{
    "success": true,
    "data": {
        "product": {
            "id": 3341,
            "code": "1234",
            "name": "name2",
            "description": "changed lorem ipsum description",
            "created_at": "2021-05-24T03:28:21.000000Z",
            "updated_at": "2021-05-24T03:45:00.000000Z",
            "deleted_at": null
        }
    },
    "message": "Product Updated successfully."
}
```

-   DELETE [http://test.local/api/products/{id}](#)

#### Sample response

```
{
    "success": true,
    "data": [],
    "message": "Product Deleted successfully."
}
```

### Add a stock onHand for a product.

-   POST [http://test.local/api/add-stock](#)

#### Sample response

```
{
    "success": true,
    "data": {
        "stock": {
            "product_id": 3341,
            "on_hand": "10",
            "production_date": "2020-08-26T00:00:00.000000Z",
            "updated_at": "2021-05-24T03:47:37.000000Z",
            "created_at": "2021-05-24T03:47:37.000000Z",
            "id": 6742
        }
    },
    "message": "Stock Added successfully."
}
```

---

### Able to get products and product details.

-   GET [http://test.local/api/products](#)

#### Sample response

```
{
    "success": true,
    "data": {
        "products": [
            {
                "id": 1,
                "code": "229113",
                "name": "B-ED PIZZLES BP",
                "description": "B-ED PIZZLES BP",
                "created_at": "2021-05-24T02:20:27.000000Z",
                "updated_at": "2021-05-24T03:49:30.000000Z",
                "deleted_at": null
            },
            {
                "id": 2,
                "code": "40330",
                "name": "B-INS 100VL IW FZ",
                "description": "B-INS 100VL IW FZ",
                "created_at": "2021-05-24T02:20:27.000000Z",
                "updated_at": "2021-05-24T03:49:30.000000Z",
                "deleted_at": null
            },
            ...
        ]
    },
    "message": "Products retrieved successfully"
}
```

-   GET [http://test.local/api/products/{product}](#)

#### Sample response

```
{
    "success": true,
    "data": {
        "product": {
            "id": 171,
            "code": "214751",
            "name": "BF-TAILS IW FSB",
            "description": "BF-TAILS IW FSB",
            "created_at": "2021-05-24T02:20:27.000000Z",
            "updated_at": "2021-05-24T03:49:30.000000Z",
            "deleted_at": null
        }
    },
    "message": "Product retrieved successfully."
}
```

---

### Able to pass optional stock parameter in get products and product details API to get stock onHand summary.

-   GET [http://test.local/api/products?with_stock_quantity=yes](#)

#### Sample response

```
{
    "success": true,
    "data": {
        "products": [
            {
                "id": 1,
                "product_id": 12,
                "stock_id": 62,
                "stock_quantity": null
                "on_hand": 1,
                "taken": null,
                "production_date": "2020-07-29 00:00:00",
                "code": "229113",
                "name": "B-ED PIZZLES BP",
                "description": "B-ED PIZZLES BP",
                "created_at": "2021-05-24T02:20:27.000000Z",
                "updated_at": "2021-05-24T03:49:30.000000Z",
                "deleted_at": null
            },
            {
                "id": 2,
                "product_id": 12,
                "stock_id": 62,
                "stock_quantity": "344"
                "on_hand": 1,
                "taken": null,
                "production_date": "2020-07-29 00:00:00",
                "code": "40330",
                "name": "B-INS 100VL IW FZ",
                "description": "B-INS 100VL IW FZ",
                "created_at": "2021-05-24T02:20:27.000000Z",
                "updated_at": "2021-05-24T03:49:30.000000Z",
                "deleted_at": null
            },
            ...
        ]
    },
    "message": "Products retrieved successfully"
}
```

-   GET [http://test.local/api/products/{product}?with_stock_quantity=yes](#)

#### Sample response

```
{
    "success": true,
    "data": {
        "product": {
            "id": 21,
            "code": "214751",
            "name": "BF-TAILS IW FSB",
            "description": "BF-TAILS IW FSB",
            "created_at": "2021-05-24T02:20:54.000000Z",
            "updated_at": "2021-05-24T02:20:54.000000Z",
            "deleted_at": null,
            "product_id": 171,
            "on_hand": 1,
            "taken": null,
            "production_date": "2020-07-01 00:00:00",
            "stock_id": 21,
            "stock_quantity": "46"
        }
    },
    "message": "Product retrieved successfully."
}
```

---

### Able to sort products by stock onHand by both asc and desc order.

-   GET [http://test.local/api/products?with_stock_quantity=yes&stock_sort_by=desc](#)

#### Sample response with desc order

```
{
    "success": true,
    "data": {
        "products": [
            {
                "id": 6737,
                "code": "38005",
                "name": "CARVERY LEG (M/LX)",
                "description": "CARVERY LEG (M/LX)",
                "created_at": "2021-05-24T02:21:02.000000Z",
                "updated_at": "2021-05-24T02:21:02.000000Z",
                "deleted_at": null,
                "product_id": 972,
                "on_hand": 500,
                "taken": null,
                "production_date": "2020-06-09 00:00:00",
                "stock_id": 6737,
                "stock_quantity": "1400"
            },
            {
                "id": 4339,
                "code": "17657",
                "name": "PS-CHK BP FZ CN",
                "description": "PS-CHK BP FZ CN",
                "created_at": "2021-05-24T02:20:59.000000Z",
                "updated_at": "2021-05-24T02:20:59.000000Z",
                "deleted_at": null,
                "product_id": 1315,
                "on_hand": 1,
                "taken": null,
                "production_date": "2020-06-16 00:00:00",
                "stock_id": 4339,
                "stock_quantity": "1320"
            },
             ...
        ]
    },
    "message": "Products retrieved successfully"
}
```

#### Sample response with asc order

```
{
    "success": true,
    "data": {
        "products": [
            {
                "id": 4339,
                "code": "17657",
                "name": "PS-CHK BP FZ CN",
                "description": "PS-CHK BP FZ CN",
                "created_at": "2021-05-24T02:20:59.000000Z",
                "updated_at": "2021-05-24T02:20:59.000000Z",
                "deleted_at": null,
                "product_id": 1315,
                "on_hand": 1,
                "taken": null,
                "production_date": "2020-06-16 00:00:00",
                "stock_id": 4339,
                "stock_quantity": "1320"
            },
            {
                "id": 6737,
                "code": "38005",
                "name": "CARVERY LEG (M/LX)",
                "description": "CARVERY LEG (M/LX)",
                "created_at": "2021-05-24T02:21:02.000000Z",
                "updated_at": "2021-05-24T02:21:02.000000Z",
                "deleted_at": null,
                "product_id": 972,
                "on_hand": 500,
                "taken": null,
                "production_date": "2020-06-09 00:00:00",
                "stock_id": 6737,
                "stock_quantity": "1400"
            },
             ...
        ]
    },
    "message": "Products retrieved successfully"
}
```

---

### Able to filter products by stock availability.

-   GET [http://test.local/api/products/{product}?stock_only=yes](#)

#### Sample response

```
{
    "success": true,
    "data": {
        "products": [
            {
                "id": 62,
                "code": "49354",
                "name": "B-FH 92CL BP FZ PRE",
                "description": "B-FH 92CL BP FZ PRE",
                "created_at": "2021-05-24T02:20:54.000000Z",
                "updated_at": "2021-05-24T02:20:54.000000Z",
                "deleted_at": null,
                "product_id": 92,
                "on_hand": 1,
                "taken": null,
                "production_date": "2020-07-29 00:00:00",
                "stock_id": 62,
                "stock_quantity": "344"
            },
            {
                "id": 45,
                "code": "494184",
                "name": "B-FH 95CL BP CARGILL",
                "description": "B-FH 95CL BP CARGILL",
                "created_at": "2021-05-24T02:20:54.000000Z",
                "updated_at": "2021-05-24T02:20:54.000000Z",
                "deleted_at": null,
                "product_id": 98,
                "on_hand": 1,
                "taken": null,
                "production_date": "2020-06-23 00:00:00",
                "stock_id": 45,
                "stock_quantity": "174"
            },
            ...
            ]
    },
    "message": "Products retrieved successfully"
}
```

---

### Able to bulk (5k +) insert/update products into database

-   POST [http://test.local/api/import-products](#)

#### Sample success response

```
{
    "success": true,
    "data": [],
    "message": "Products imported successfully."
}

```

---

### Able to bulk (20k +) insert stock into the database.

-   POST [http://test.local/api/import-stock](#)

#### Sample success response

```
{
    "success": true,
    "data": [],
    "message": "Stock imported successfully."
}
```

---

## Technical Specifications

-   Install all required packages by running command [Composer install].
-   Create database and edit the .env file with database credentials.
-   run `php artisan migrate:fresh --seed`
-   To Run Unit and Feature test
    `vendor/bin/phpunit`

---
