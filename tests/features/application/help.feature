Feature: Help Option

    As a user
    I want to see a list of avaliable arguments and options
    So that I can work out how to use the application

    Scenario Outline: I run the application with the help option
        When I call the application with "<option>"
        Then I should see:
            """
            Usage:
              static-review [options] [--] [<path>]...

            Arguments:
              path                   Spefify the folder to review [default: ["."]]

            Options:
              -c, --config=CONFIG    Specify a configuration file to use
                  --adapter=ADAPTER  Specify the adapter to use
                  --review=REVIEW    Specify the reviews to use (multiple values allowed)
              -h, --help             Display this help message
              -q, --quiet            Do not output any message
              -V, --version          Display the application version
                  --update           Update the application to the latest version
                  --ansi             Force ANSI output
                  --no-ansi          Disable ANSI output
              -n, --no-interaction   Do not ask any interactive question
              -v|vv|vvv, --verbose   Increase the verbosity of the output
            """
        And the application should exit successfully

        Examples:
            | option |
            | -h     |
            | --help |
