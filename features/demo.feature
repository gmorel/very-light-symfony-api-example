Feature:
    In order to allow a User to create a Product
    As a User
    I want to save my Product info


    Scenario: It creates a Product
        When I send a POST request to "/v1/en/products.json" with body:
        """
            {
                "id": "fabd5e92-02e7-43f7-a962-adab8ec88e94",
                "name": "Product1"
            }
        """
        Then the response status code should be 201
        And the header "Location" should contain "/v1/products/fabd5e92-02e7-43f7-a962-adab8ec88e94.json"


    Scenario: It fails to create a Product with missing a node
        When I send a POST request to "/v1/en/products.json" with body:
        """
            {
                "id": "fabd5e92-02e7-43f7-a962-adab8ec88e94"
            }
        """
        Then the response status code should be 400
        And the JSON node "type" should be equal to "Missing JSON node"
        And the JSON node "title" should be equal to "Bad Request"
        And the JSON node "violations" should have 0 element


    Scenario: It fails to create a Product with bad uuid and empty name
        When I send a POST request to "/v1/en/products.json" with body:
        """
            {
                "id": "fabd5e92-02e7-43f7-a962-adab8ec88e9",
                "name": ""
            }
        """
        Then the response status code should be 400
        And the JSON node "type" should be equal to "UI Validation"
        And the JSON node "title" should be equal to "Bad Request"
        And the JSON node "violations" should have 2 elements
        And the JSON node "violations[0].propertyPath" should be equal to "id"
        And the JSON node "violations[0].message" should be equal to "This is not a valid UUID."
        And the JSON node "violations[1].propertyPath" should be equal to "name"
        And the JSON node "violations[1].message" should be equal to "This value should not be blank."


    Scenario: It fails to create a Product in French
        Given I add "Accept-Language" header equal to "fr"
        When I send a POST request to "/v1/fr/products.json" with body:
        """
            {
                "id": "fabd5e92-02e7-43f7-a962-adab8ec88e9",
                "name": ""
            }
        """
        Then the response status code should be 400
        And the JSON node "type" should be equal to "UI Validation"
        And the JSON node "title" should be equal to "Bad Request"
        And the JSON node "violations" should have 2 elements
        And the JSON node "violations[0].propertyPath" should be equal to "id"
        And the JSON node "violations[0].message" should be equal to "Ceci n'est pas un UUID valide."
        And the JSON node "violations[1].propertyPath" should be equal to "name"
        And the JSON node "violations[1].message" should be equal to "Cette valeur ne doit pas être vide."
