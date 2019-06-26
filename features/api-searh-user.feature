@api @current
Feature: Check api route api-search-user
  Scenario: It receives a response from search user api endpoint, validate base response structure
    When I request "/api/user/search" using HTTP "GET"
    Then the response code is 200
    Then the response body contains JSON:
      """
      {
          "items": "@variableType(array)",
          "next_page": "@variableType(int)",
          "prev_page": "@variableType(null)",
          "total_items": "@variableType(int)",
          "current_page": "@variableType(int)",
          "page_size": "@variableType(int)",
          "total_pages": "@variableType(int)"
      }
      """
    Then the response body contains JSON:
      """
      {
          "items": [
              {
                  "id": "@variableType(int)",
                  "name": "@variableType(string)",
                  "email": "@variableType(string)",
                  "status": "@variableType(string)"
              }
          ]
      }
      """
  Scenario: It receives a response from search user api endpoint, validate possibility to use pagination
    When I request "/api/user/search?page=2&page_size=2" using HTTP "GET"
    Then the response code is 200
    Then the response body contains JSON:
      """
      {
          "items": "@arrayLength(2)",
          "next_page": "@variableType(int)",
          "prev_page": "@variableType(int)",
          "current_page": 2,
          "page_size": 2
      }
      """
  Scenario: It receives a response from search user api endpoint, validate possibility filter users by email
    When I request "/api/user/search?email=test_user0@test.com" using HTTP "GET"
    Then the response code is 200
    Then the response body contains JSON:
      """
      {
          "items": "@arrayLength(1)",
          "next_page": "@variableType(null)",
          "prev_page": "@variableType(null)",
          "current_page": 1,
          "total_items": 1,
          "total_pages": 1
      }
      """

  Scenario: It receives a response from search user api endpoint, validate possibility filter users by having posts and havig comments
    When I request "/api/user/search?user_have_comments=true&user_have_posts" using HTTP "GET"
    Then the response code is 200
    Then the response body contains JSON:
      """
      {
          "items": "@variableType(array)",
          "current_page": 1,
          "total_items": "@lt(100)"
      }
      """