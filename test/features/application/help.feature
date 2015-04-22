Feature: Help Option

    As a user
    I want to see a list of avaliable arguments and options
    So that I can work out how to use the application

    Scenario Outline: I run the application with the help option
        When I call the application with "<option>"
        Then I should see:
            """
            Usage:
             static-review [-c|--config="..."] [-d|--driver="..."] [-f|--format="..."] [-r|--review="..."]

            Options:
             --config (-c)         Specify a configuration file to use
             --driver (-d)         Specify the driver to use (file is default)
             --format (-f)         Specify the format of the output (progress is default)
             --review (-r)         Specify the reviews to use (multiple values allowed)
             --help (-h)           Display this help message
             --quiet (-q)          Do not output any message
             --verbose (-v|vv|vvv) Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug
             --version (-V)        Display this application version
             --ansi                Force ANSI output
             --no-ansi             Disable ANSI output
            """
        And the application should exit successfully

        Examples:
            | option |
            | -h     |
            | --help |
