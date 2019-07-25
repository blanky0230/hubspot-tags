# hubspot-tags

Very basic integration of custom "Tags" into your Hubspot CRM.

Suppose you add the strings 'DEMO' or 'CLOSE' to engagements with contacts.

This tool can help you generate some reporting as to how often on a certain day you've used these strings in ANY engagement.

Currently supported are 'DEMO' and 'CLOSE'. If you have any activity for a CRM-Contact that contains these strings, they're counted as a Close- or Demo-Tag respectively.

The statistics are grouped by days. Days that have NO tag will not be shown.

Example output:
```
|Day        |CLOSE |DEMO  |
|2017-02-07 |1     |2     |
|2017-02-08 |1     |0     |
```

You can specify a contact's Email in order to only report in respect of that contact.

```
Usage:
  generate-report [options] [--] [<contact-email>]

Arguments:
  contact-email         The contact email for generating reports in the scope of a single contact.

Options:
  -j, --json            Generate JSON-Output.
  -h, --help            Display this help message
  -q, --quiet           Do not output any message
  -V, --version         Display this application version
      --ansi            Force ANSI output
      --no-ansi         Disable ANSI output
  -n, --no-interaction  Do not ask any interactive question
  -v|vv|vvv, --verbose  Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug
```

### Run it locally

#### Prerequisites: 

Either docker & docker-compose or php>=7.3 and composer.
Your Hubspot API-Key. (Read as environment variable).

Either add your Hubspot API-Key to the docker-compose environment mapping or provide it via `-e HAPIKEY=XXX`.


1. Clone the repo
```
git clone git@github.com:blanky0230/hubspot-tags.git
```

2. Install dependencies
```
 docker-compose run -T hubspot-tags composer install
```

3. Generate a report
```
 docker-compose run -e HAPIKEY=THIS_IS_A_SECRET -T hubspot-tags  php hubspot-tags.php generate-report
```


### Current Improvements Possibilities

I've added the "META-TAG" to be able to dynamically create such text-based reporsts with other words as well.

Currently I did not bother to handle pagination With the Hubspot API see the next seciont.

### Challenges

The Hubspot API contains MANY Resources that have a lot of relations with each other.
However, even though it's 2019 the API (seemingly!) does not really seem to provide some sort of HATEOAS-Support which made me a little bit sad.


### Used Technologies

Obviously PHP. But also [php-cs-fixer](https://github.com/FriendsOfPhp/PHP-CS-Fixer) for formatting, [phpstan](https://github.com/phpstan/phpstan) for static analysis and finding errors before they occur!
The 'Domain' part is mostly TDD and a trial to adhere to DDD-Principles, with the help of the great [phpunit](https://github.com/sebastianbergmann/phpunit)

Libraries:

[guzzle/http](http://docs.guzzlephp.org/en/stable/) and [symfony/console](https://symfony.com/doc/current/console) :)

### Advantages

The "Domain" pieces can pretty be used in any context, regardless of the Hubspot integration!
