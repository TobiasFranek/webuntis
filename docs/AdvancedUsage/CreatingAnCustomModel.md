# Creating an custom Model

Why would you create a custom Model? It is simple, what if you want different fields or you want to add a Model that doesn't exist yet.

First you have to learn how a Model is assembled every model inherits from the AbstractModel and there are mandatory methods like parse() and serialize(). But it is important to know that you implement the right Interfaces because the Interfaces say if the Model is Cacheable or need Adminrights to execute the command.

if you have installed the package over composer you can use this command

```shell
php vendor/tfranek/webuntis/bin/console.php webuntis:generate:model
```

follow the instruction in the console.

## Types

For the models there are different types and these are defined as types, types that are integrated in the core are:

* int - resembles the int type
* string - is a string
* date - is a \DateTime that consist of on date
* mergeTimeAndDate - is a type that merges time and date to one \DateTime
* model - is an subordinate object that need parameter to find it in the api
* modelCollection - is an collection of subordinate objects

## YML Configuration

All your configuration that you set(fieldnames, custom repos) are saved in .yml files with these files the class can automatically generate a parse() function to assign the right api value to the right model values.
