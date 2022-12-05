# Advent of Code 2022

https://adventofcode.com/2022


## Setup

### With local PHP 8.1

```shell
make install
```


### Using Docker

```shell
make d_install
```


## Running a Day of AoC

```shell
cat 1.input | bin/consle aoc:<day>
```

Running without any arguments will run part one and two.


```shell
Usage:
  aoc:1 [options]

Options:
  -1, --one             Execute part One
  -2, --two             Execute part Two
```


## Test

All days have a unittest that tests the example input and output.

```shell
bin/phpunit
```
