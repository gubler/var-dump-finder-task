# var-dump-finder-task

[Phing](https://www.phing.info/) tasks to check for `var_dump()` usage in
your source code. This is helpful as part of precommit checks to make
sure you don't commit debug code into your repository.

## Installation

Install this package through [Composer](https://getcomposer.org/):

```
composer.phar require gubler/var-dump-finder-task
```

## Setup

You can import the task into your build:

```xml
<import file="./vendor/gubler/var-dump-finder-task/task.xml"/>
```

Or define the `vardumpfinder` task in your `build.xml` file:

```xml
<taskdef name="vardumpfinder" classname="Gubler\Phing\VarDumpFinderTask\VarDumpFinderTask" />
```

## Usage

VarDumpFinderTask has two attributes and requires a `<fileset>` of files to check.

#### Attributes
| Name | Type | Description | Default |
| --- | --- | --- | --- |
| haltonmatch | Boolean | halt and fail the build if match found  | false |
| findincomments | Boolean | find uses of var_dump in comments | false |

#### Example
```xml
<vardumpfinder
      haltonmatch="true"
      findincomments="true"
>
      <fileset />
</vardumpfinder>
```

## License

gubler/var-dump-finder-task is released under the MIT license.
