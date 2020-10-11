This project recreates the functionality of a shopping cart. To accomplish it, the following use cases have being implemented:  
* Add a product into the shopping cart.
* A product can have a discount price based on the units inside the shopping cart.
* A product can't have more than 50 units of its own inside the shopping cart.
* The shopping cart can't have more than 10 different products inside.
* A product can be removed from the shopping cart.
* You can get total of the shopping cart by currency, using ECB API when the currency is different to EUR.

The project has being coded using Hexagonal Architecture and CQRS. In this case, to simplify the scenario,
an InMemory repository has been used to store the products and shopping carts. The framework used to build the project is Symfony.

To make sure the actions against the shopping cart are met, different guards methods and custom exceptions have been created.  

The way used to validate the use cases is through the tests.

#Setup
1. Clone this repository
2. At the root of the project execute `docker-compose up -d`
3. Install project requirements. Run `docker exec -it project-shopping-cart-php composer install`.

 #Testing
 * Tu run the test run `docker exec -it project-shopping-cart-php bin/phpunit --testdox`