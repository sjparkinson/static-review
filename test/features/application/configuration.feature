Feature: Configuration File

    As a user
    I want to configure the application with a file
    So that I can use the same tool in diffrent projects

    Scenario: I run the application with a valid configuration
        Given the class file "src/Driver/ExampleDriver.php" contains:
            """
            <?php
            use MainThread\StaticReview\Driver\DriverInterface;
            class ExampleDriver implements DriverInterface
            {
                public function supports($path)
                {
                    return true;
                }

                public function getFiles($path)
                {
                    return [];
                }
            }
            """
        And the class file "src/Formatter/ExampleFormatter.php" contains:
            """
            <?php
            use MainThread\StaticReview\Formatter\FormatterInterface;
            class ExampleFormatter implements FormatterInterface
            {
                public function getPrinter()
                {
                    return null;
                }

                public function getResultsCollector()
                {
                    return null;
                }
            }
            """
        And the class file "src/Review/ExampleReview.php" contains:
            """
            <?php
            use MainThread\StaticReview\Review\ReviewInterface;
            use MainThread\StaticReview\File\FileInterface;
            class ExampleReview implements ReviewInterface
            {
                public function supports(FileInterface $file) { return true; }
                public function review(FileInterface $file)
                {
                    return 1;
                }
            }
            """
        And the configuration file contains:
            """
            driver: ExampleDriver
            reviews: null
            formatter: ExampleFormatter
            """
        When I run the application
        Then the application should have loaded "config.driver" with "ExampleDriver"
        And the application should have loaded "config.formatter" with "ExampleFormatter"
        And the application should exit successfully

    Scenario Outline: I run the application using the diffrent configuration file names
        Given the file "<filename>" contains:
            """
            driver: ExampleDriver
            reviews: null
            formatter: ExampleFormatter
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
            driver: ExampleDriver
            reviews: ClassNotFound
            formatter: ExampleFormatter
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
            driver: ExampleDriver
            reviews: null
            formatter: ExampleFormatter
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
             Configuration requires values for `driver`, `reviews`, and `formatter`.
             """

    Scenario: I run the application with a missing field
        Given the configuration file contains:
            """
            driver: ExampleDriver
            formatter: ExampleFormatter
            """
        When I run the application
        Then the application should throw a "ConfigurationException" with:
             """
             Configuration requires values for `driver`, `reviews`, and `formatter`.
             """

    Scenario: I run the application with all the configuration specified in the command line
        When I call the application with "--driver ExampleDriver --formatter ExampleFormatter --review ExampleReview"
        Then the application should exit successfully

    Scenario: I run the application with driver and formatter specified as command line options
        Given the configuration file contains:
            """
            reviews: null
            """
        When I call the application with "--driver ExampleDriver --formatter ExampleFormatter"
        Then the application should exit successfully

    Scenario: I run the application with driver specified as a command line option
        Given the configuration file contains:
            """
            reviews: null
            formatter: ExampleFormatter
            """
        When I call the application with "--driver ExampleDriver"
        Then the application should exit successfully

    Scenario: I run the application with formatter specified as a command line option
        Given the configuration file contains:
            """
            driver: ExampleDriver
            reviews: null
            """
        When I call the application with "--formatter ExampleFormatter"
        Then the application should exit successfully

    Scenario: I run the application and override configuration using the command line options
        Given the configuration file contains:
            """
            driver: null
            reviews: null
            formatter: null
            """
        When I call the application with "--formatter ExampleFormatter --driver ExampleDriver"
        Then the application should have loaded "config.driver" with "ExampleDriver"
        And the application should have loaded "config.formatter" with "ExampleFormatter"
        And the application should exit successfully
