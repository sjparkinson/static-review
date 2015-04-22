Feature: Configuration File

    As a user
    I want to configure the application with a file
    So that I can use the same tool in diffrent projects

    Scenario: I run the application with a valid configuration
        Given the configuration file contains:
            """
            driver: git
            reviews: null
            format: progress
            """
        When I run the application
        Then the application should have loaded "config.driver" with "git"
        And the application should have loaded "config.format" with "progress"
        And the application should exit successfully

    Scenario: I specify a review that doesn't exist
        Given the configuration file contains:
            """
            driver: git
            reviews: ClassNotFound
            format: progress
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
            No configuration file found, and no command line options specified.
            """

    Scenario Outline: I specify a configuration that doesn't exist
        When I call the application with "<option> test.yml"
        Then the application should throw a "ConfigurationException" with:
            """
            No configuration file found, and no command line options specified.
            """

        Examples:
            | option   |
            | -c       |
            | --config |

    Scenario: I specifiy a configuration file
        Given the file "test.yml" contains:
            """
            driver: git
            reviews: null
            format: progress
            """
        When I call the application with "--config test.yml"
        Then the application should exit successfully

    Scenario: I run the application with a totally invalid configuration
        Given the configuration file contains:
            """
            foo: bar
            """
        When I run the application
        Then the application should throw a "ConfigurationException" with:
             """
             Configuration requires values for `driver`, `reviews`, and `format`.
             """

    Scenario: I run the application with a missing field
        Given the configuration file contains:
            """
            driver: git
            format: progress
            """
        When I run the application
        Then the application should throw a "ConfigurationException" with:
             """
             Configuration requires values for `driver`, `reviews`, and `format`.
             """

    Scenario: I run the application with all the configuration specified in the command line
        When I call the application with "--driver file --format progress --review MainThread\\StaticReview\\Review\\Review"
        Then the application should exit successfully

    Scenario: I run the application with driver and format specified as command line options
        Given the configuration file contains:
            """
            reviews: null
            """
        When I call the application with "--driver file --format progress"
        Then the application should exit successfully

    Scenario: I run the application with driver specified as a command line option
        Given the configuration file contains:
            """
            reviews: null
            format: progress
            """
        When I call the application with "--driver file"
        Then the application should exit successfully

    Scenario: I run the application with format specified as a command line option
        Given the configuration file contains:
            """
            driver: file
            reviews: null
            """
        When I call the application with "--format progress"
        Then the application should exit successfully

    Scenario: I run the application and override configuration using the command line options
        Given the configuration file contains:
            """
            driver: file
            reviews: null
            format: pretty
            """
        When I call the application with "--format progress --driver git"
        Then the application should have loaded "config.driver" with "git"
        And the application should have loaded "config.format" with "progress"
        And the application should exit successfully
