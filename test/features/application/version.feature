Feature: Application Version Information

    As a user
    I want to be able to see what version of the application I'm using
    So that I can provide useful information when submitting bug requests

    Background:
        Given the configuration file contains:
            """
            vcs: git
            reviews: null
            formatter: progress
            """

    Scenario: I ask for the application version
        When I call the application with "--version"
        Then I should see:
            """
            static-review version development
            """
        And the application should exit successfully
