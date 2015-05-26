Feature: Configuration File

    As a user
    I want to configure the application with a file
    So that I can use the same tool in diffrent projects

    Scenario: I run the application with a valid configuration
        Given the configuration file contains:
            """
            adapter: filesystem
            reviews: [ StaticReview\StaticReview\Test\Review\PassReview ]
            """
        When I run the application
        Then the application should exit successfully

    Scenario Outline: I run the application using the diffrent configuration file names
        Given the file "<filename>" contains:
            """
            adapter: filesystem
            reviews: [ StaticReview\StaticReview\Test\Review\PassReview ]
            """
        When I run the application
        Then the application should exit successfully

        Examples:
            | filename                |
            | .static-review.yml      |
            | .static-review.yml.dist |
            | static-review.yml       |
            | static-review.yml.dist  |

    Scenario Outline: I specify a configuration that doesn't exist
        When I call the application with "<option> test.yml"
        Then the application should throw a "RuntimeException" with:
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
            adapter: filesystem
            reviews: [ StaticReview\StaticReview\Test\Review\PassReview ]
            """
        When I call the application with "--config test.yml"
        Then the application should exit successfully

    Scenario: I run the application with all the configuration specified in the command line
        When I call the application with "--adapter filesystem --review StaticReview\StaticReview\Test\Review\PassReview"
        Then the application should exit successfully

    Scenario: I run the application with adapter specified as a command line option
        Given the configuration file contains:
            """
            reviews: [ StaticReview\StaticReview\Test\Review\PassReview ]
            """
        When I call the application with "--adapter filesystem"
        Then the application should exit successfully

    Scenario: I run the application and override configuration using the command line options
        Given the configuration file contains:
            """
            adapter: null
            reviews: [ StaticReview\StaticReview\Test\Review\PassReview ]
            """
        When I call the application with "--adapter filesystem"
        Then what did I see?
        Then the application should exit successfully
