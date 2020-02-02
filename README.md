# Ajaro Web Developer Test

## Requirements

* PHP >= 7.2
* Composer
* PostgreSQL 10

## How to run (local)

* clone this project
* create new database from your PostgreSQL server
* setup your `.env`
* run the migration `php artisan migrate`
* run seeder `php artisan db:seed`
* run the server `php artisan serve`
* and now you can perform login using postman by accessing `[POST] localhost:8000/api/v1/login` and see dummy account

## Docs

* please check the api routing inside `routes/api.php`
* dummy account, please check `database/seeds/DatabaseSeeder.php`

## Tasks

### Required Tasks

- [ ] make a product purchase transaction feature (cash payment)
    - [ ] table `trx_purchases` and `trx_purchase_details`
    - [ ] model `TrxPurchase`
    - [ ] api
        - [ ] can show all with paginate
        - [ ] can show by id
        - [ ] can delete by id
        - [ ] can save new purchase transaction
- [ ] make a product sales transaction feature (cash payment)
- [ ] table `trx_sales` and `trx_sale_details`
    - [ ] model `TrxSale`
    - [ ] api
        - [ ] can show all with paginate
        - [ ] can show by id
        - [ ] can delete by id
        - [ ] can save new purchase transaction
- [ ] create the UI
    - [ ] implement login api
    - [ ] implement logout api
    - [ ] implement save new sales transaction
    - [ ] implement save new purchase transaction

### Optional Tasks

- [ ] create docker image
- [ ] create automation to check codestyle with psr standard

## How to Submit

* create repository on your github with name `ajaro-webdev-test`
* push your code in that repository
* please inform your repository url to our email : [mail@ajaro.id](mailto:mail@ajaro.id)

> Good Luck!
