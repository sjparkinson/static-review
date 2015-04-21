Feature: Configuration File

    As a user
    I want to configure the application with a file
    So that I can use the same tool in diffrent projects

    Scenario: I run the application with a valid configuration
        Given the configuration file contains:
            """
            vcs: git
            reviews: null
            formatter: progress
            """
        When I run the application
        Then the application should have loaded "config.vcs" with "git"
        And the application should have loaded "config.formatter" with "progress"
        And the application should exit successfully

    Scenario: I specify a review that doesn't exist
        Given the configuration file contains:
            """
            vcs: git
            reviews: ClassNotFound
            formatter: progress
            """
        When I run the application
        And the application should throw a "ConfigurationException" with:
            """
            Review class `ClassNotFound` does not exist.
            """

    Scenario: I run the application with no configuration
        When I run the application
        Then the application should throw a "ConfigurationException" with:
            """
            Configuration file not found.
            """

    Scenario: I specify a configuration that doesn't exist
        When I call the application with "--config test.yml"
        Then the application should throw a "ConfigurationException" with:
            """
            Configuration file not found.
            """

    Scenario: I run the application with a totally invalid configuration
        Given the configuration file contains:
            """
            foo: bar
            """
        When I run the application
        Then the application should throw a "ConfigurationException" with:
             """
             Configuration file requires values for `vcs`, `reviews`, and `formatter`.
             """

    Scenario: I run the application with a missing field
        Given the configuration file contains:
            """
            vcs: git
            formatter: progress
            """
        When I run the application
        Then the application should throw a "ConfigurationException" with:
             """
             Configuration file requires values for `vcs`, `reviews`, and `formatter`.
             """
