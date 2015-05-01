Feature: Output Formatters

    As a user
    I would like to specify the format of the applications output
    So that I can use the most useful output format

    Supported formatters:
        - progress

    Scenario: I run the application with the progress formatter
        Given the class file "src/Review/SkipReview.php" contains:
            """
            <?php
            use MainThread\StaticReview\Review\ReviewInterface;
            use MainThread\StaticReview\File\FileInterface;
            use MainThread\StaticReview\Result\Result;
            class PassReview implements ReviewInterface
            {
                public function supports(FileInterface $file) { return true; }
                public function review(FileInterface $file)
                {
                    return new Result(1, $file, $this);
                }
            }
            """
        And the configuration file contains:
            """
            driver: MainThread\StaticReview\Driver\LocalDriver
            reviews:
                - PassReview
            formatter: MainThread\StaticReview\Formatter\ProgressFormatter
            """
        And the file "test.txt" contains:
            """
            Testing file.
            """
        When I run the application
        Then I should see:
            """
            ...
            """
        And the application should exit successfully
