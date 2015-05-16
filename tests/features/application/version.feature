Feature: Application Version Information

    As a user
    I want to be able to see what version of the application I'm using
    So that I can provide useful information when submitting bug requests

    Scenario Outline: I ask for the application version
        When I call the application with "<option>"
        Then I should see:
            """
            static-review version behat
            """
        And the application should exit successfully

        Examples:
            | option    |
            | --version |
            | -V        |
