Feature: Configuration File

    As a user
    I want to configure the application with a file
    So that I can use the same tool in diffrent projects

    Scenario: I run the application with a valid configuration
        Given the configuration file contains:
            """
            adapter: MainThread\StaticReview\Adapter\FilesystemAdapter
            reviews: [ MainThread\StaticReview\Review\NoCommitReview ]
            """
        When I run the application
        Then the application should exit successfully

    Scenario Outline: I run the application using the diffrent configuration file names
        Given the file "<filename>" contains:
            """
            adapter: MainThread\StaticReview\Adapter\FilesystemAdapter
            reviews: [ MainThread\StaticReview\Review\NoCommitReview ]
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
            adapter: MainThread\StaticReview\Adapter\FilesystemAdapter
            reviews: [ MainThread\StaticReview\Review\NoCommitReview ]
            """
        When I call the application with "--config test.yml"
        Then the application should exit successfully

    Scenario: I run the application with all the configuration specified in the command line
        When I call the application with "--adapter MainThread\StaticReview\Adapter\FilesystemAdapter --review MainThread\StaticReview\Review\NoCommitReview"
        Then the application should exit successfully

    Scenario: I run the application with adapter specified as a command line option
        Given the configuration file contains:
            """
            reviews: [ MainThread\StaticReview\Review\NoCommitReview ]
            """
        When I call the application with "--adapter MainThread\StaticReview\Adapter\FilesystemAdapter"
        Then the application should exit successfully

    Scenario: I run the application and override configuration using the command line options
        Given the configuration file contains:
            """
            adapter: null
            reviews: [ MainThread\StaticReview\Review\NoCommitReview ]
            """
        When I call the application with "--adapter MainThread\StaticReview\Adapter\FilesystemAdapter"
        Then the application should exit successfully
