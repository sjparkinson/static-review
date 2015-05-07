Feature: Configuration File

    As a user
    I want to configure the application with a file
    So that I can use the same tool in diffrent projects

    Scenario: I run the application with a valid configuration
        Given the configuration file contains:
            """
            adapter: MainThread\StaticReview\Adapter\FilesystemAdapter
            reviews: [ MainThread\StaticReview\Review\NoCommitReview ]
            formatter: MainThread\StaticReview\Formatter\ProgressFormatter
            """
        When I run the application
        Then the application should have loaded "config.adapter" with "MainThread\StaticReview\Adapter\FilesystemAdapter"
        And the application should have loaded "config.formatter" with "MainThread\StaticReview\Formatter\ProgressFormatter"
        And the application should exit successfully

    Scenario Outline: I run the application using the diffrent configuration file names
        Given the file "<filename>" contains:
            """
            adapter: MainThread\StaticReview\Adapter\FilesystemAdapter
            reviews: [ MainThread\StaticReview\Review\NoCommitReview ]
            formatter: MainThread\StaticReview\Formatter\ProgressFormatter
            """
        When I run the application
        Then the application should exit successfully

        Examples:
            | filename                |
            | .static-review.yml      |
            | .static-review.yml.dist |
            | static-review.yml       |
            | static-review.yml.dist  |

    Scenario: I specify a review that doesn't exist
        Given the configuration file contains:
            """
            adapter: MainThread\StaticReview\Adapter\FilesystemAdapter
            reviews: ClassNotFound
            formatter: MainThread\StaticReview\Formatter\ProgressFormatter
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
            adapter: MainThread\StaticReview\Adapter\FilesystemAdapter
            reviews: [ MainThread\StaticReview\Review\NoCommitReview ]
            formatter: MainThread\StaticReview\Formatter\ProgressFormatter
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
             Configuration requires values for `adapter`, `reviews`, and `formatter`.
             """

    Scenario: I run the application with a missing field
        Given the configuration file contains:
            """
            adapter: MainThread\StaticReview\Adapter\FilesystemAdapter
            formatter: MainThread\StaticReview\Formatter\ProgressFormatter
            """
        When I run the application
        Then the application should throw a "ConfigurationException" with:
             """
             Configuration requires values for `adapter`, `reviews`, and `formatter`.
             """

    Scenario: I run the application with all the configuration specified in the command line
        When I call the application with "--adapter MainThread\StaticReview\Adapter\FilesystemAdapter --formatter MainThread\StaticReview\Formatter\ProgressFormatter --review MainThread\StaticReview\Review\NoCommitReview"
        Then the application should exit successfully

    Scenario: I run the application with adapter and formatter specified as command line options
        Given the configuration file contains:
            """
            reviews: [ MainThread\StaticReview\Review\NoCommitReview ]
            """
        When I call the application with "--adapter MainThread\StaticReview\Adapter\FilesystemAdapter --formatter MainThread\StaticReview\Formatter\ProgressFormatter"
        Then the application should exit successfully

    Scenario: I run the application with adapter specified as a command line option
        Given the configuration file contains:
            """
            reviews: [ MainThread\StaticReview\Review\NoCommitReview ]
            formatter: MainThread\StaticReview\Formatter\ProgressFormatter
            """
        When I call the application with "--adapter MainThread\StaticReview\Adapter\FilesystemAdapter"
        Then the application should exit successfully

    Scenario: I run the application with formatter specified as a command line option
        Given the configuration file contains:
            """
            adapter: MainThread\StaticReview\Adapter\FilesystemAdapter
            reviews: [ MainThread\StaticReview\Review\NoCommitReview ]
            """
        When I call the application with "--formatter MainThread\StaticReview\Formatter\ProgressFormatter"
        Then the application should exit successfully

    Scenario: I run the application and override configuration using the command line options
        Given the configuration file contains:
            """
            adapter: null
            reviews: [ MainThread\StaticReview\Review\NoCommitReview ]
            formatter: null
            """
        When I call the application with "--formatter MainThread\StaticReview\Formatter\ProgressFormatter --adapter MainThread\StaticReview\Adapter\FilesystemAdapter"
        Then the application should have loaded "config.adapter" with "MainThread\StaticReview\Adapter\FilesystemAdapter"
        And the application should have loaded "config.formatter" with "MainThread\StaticReview\Formatter\ProgressFormatter"
        And the application should exit successfully
