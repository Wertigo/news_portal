@api @current
Feature: Check api route api-search-user
  Scenario: It receives a response from search user api endpoint, validate base response structure
    When I request "/api/user/search" using HTTP "GET"
    Then the response code is 200
    Then the response body contains JSON:
      """
      {
          "users": "@variableType(array)"
      }
      """
    Then the response body contains JSON:
      """
      {
          "users": [
              {
                  "id": "@variableType(int)",
                  "name": "@variableType(string)",
                  "email": "@variableType(string)",
                  "status": "@variableType(string)"
              }
          ]
      }
      """
  Scenario: It receives a response from search user api endpoint
    When I request "/api/user/search" using HTTP "GET"
    Then the response code is 200
