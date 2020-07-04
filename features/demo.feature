Feature:
    In order to allow a User to create a Product
    As a User
    I want to save my Product info


    Scenario: It creates a Product
        When I send a POST request to "/v1/products.json" with body:
        """
            {
                "id": "fabd5e92-02e7-43f7-a962-adab8ec88e94",
                "name": "Product1"
            }
        """
        Then the response status code should be 201
        And the header "Location" should contain "/v1/products/fabd5e92-02e7-43f7-a962-adab8ec88e94.json"

