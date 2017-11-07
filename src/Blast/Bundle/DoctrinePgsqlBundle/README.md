# BlastDoctrinePgsqlBundle
[![Build Status](https://travis-ci.org/blast-project/DoctrinePgsqlBundle.svg?branch=master)](https://travis-ci.org/blast-project/DoctrinePgsqlBundle)
[![Coverage Status](https://coveralls.io/repos/github/blast-project/DoctrinePgsqlBundle/badge.svg?branch=master)](https://coveralls.io/github/blast-project/DoctrinePgsqlBundle?branch=master)
[![License](https://img.shields.io/github/license/blast-project/DoctrinePgsqlBundle.svg?style=flat-square)](./LICENCE.md)

[![Latest Stable Version](https://poser.pugx.org/blast-project/doctrine-pgsql-bundle/v/stable)](https://packagist.org/packages/blast-project/doctrine-pgsql-bundle)
[![Latest Unstable Version](https://poser.pugx.org/blast-project/doctrine-pgsql-bundle/v/unstable)](https://packagist.org/packages/blast-project/doctrine-pgsql-bundle)
[![Total Downloads](https://poser.pugx.org/blast-project/doctrine-pgsql-bundle/downloads)](https://packagist.org/packages/blast-project/doctrine-pgsql-bundle)


This bundle extends the Postgresql functionalities of Doctrine and Sonata projects

Features
========

For the moment, the only feature of this bundle is:

- replacing LIKE keyword by ILIKE (Postgresql specific) in sql queries in order to have case insensitive comparisons
- Substring function (regular expressions): "substring(field, regexp)" outputs "substring(field from regexp)". Postgresql specific
