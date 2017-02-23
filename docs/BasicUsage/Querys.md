# Querys 

Now to the important part, the data fetching.

You just have to create a new Query object.

```php
$query = new Query();
```

if you call
```php
$query->get('Students');
```
you get an Repository object which has already defined functions that allow you to fetch the data from the api. the get Parameter is always the model which gets the data from certain api method. 

## Repositories

every Repository has custom method the default Repository has these 2 methods:

```php
$query->get('Students')->findAll();
```
if you call this method you get all Students in this case

```php
$query->get('Students')->findBy(['firstName' => 'seppi']);
```
this method return all the students with the first name 'seppi'

you also can search recursively like this:

```php
$query->get('Period')->findBy(['teachers:firstName' => 'seppi']);
```
this will return all the Period Models where the teachers have the first name 'seppi'

you also can search if a certain string exists in a firstName like this:

```php
$query->get('Period')->findBy(['teachers:firstName' => '%epp%']);
```
you also can compare dates also:

```php
$query->get('Period')->findBy(['startDate' => '<Y-m-d']);
```

the < is how you want to compare the date < for <= and > for >=:

possible formats:
* Y-m-d H:i
* Y-m-d

you also can sort the given output

```php

$query->get('Exams')->findAll(['startDate' => 'ASC|DESC']);

//you can sort by properties that are in objects that the main objects contains, but that is restricted to one level
$query->get('Exams')->findAll(['teachers:firstName' => 'ASC|DESC']);
```

this will either order the model descending (DESC) or ascending (ASC)

you also can now give a limit to the query
```php

$query->get('Exams')->findAll(['startDate' => 'ASC|DESC'], 5);
```

### Custom Repositories

There are two custom Repositories in the core already and they are the

* PeriodRepository only has some additional parameters to the standard methods.
* SubstitutionsRepository has some additional MANDATORY parameters to the standard methods.
 
```php
//department is not mandatory
$query->get('Substitution')->findAll(department, startDate, endDate);
```
 
* UserRepository with these additional functions (This Repository can only execute these functions):

```php
$query->get('User')->getCurrentUser();
$query->get('User')->getCurrentUserType();
```

the methods mentioned before are also working on these custom repos.

### Model Usage

These are all the model that exists in the core build:

* Classes - api method: getKlassen
* Departments - api method: getDepartments
* Holidays - api method: getHolidays
* Period - api method: getTimetable
* Rooms - api method: getRooms
* Students - api method: getStudents
* Subjects - api method: getSubjects
* Teachers - api method: getTeachers
* ClassHasTeachers - show all teachers according to that class, be careful it is extremely slow
* Exams - api method: getExams
* Substitutions - api method: getSubstitutions

### Serializer

all the Repository method return the Model so an array of Model objects. If you want to serialize the Object you only need to call the serialize() method on an objects, this method then return an array.

```php
$user = $query->get('User')->getCurrentUser(); // returns an object
$user = $user->serialize(); // turn the object into an array
```

if you want an other format(supported: json, xml, yml) you have to write this:

```php
$user = $query->get('User')->getCurrentUser(); // returns an object
$user = $user->serialize('json|xml|yml'); // turn the object into an array
```

if you have an array of object you can serialize the array including the models that are in there, you have to call the Serializer Class:

```php
$students = $query->get('Students')->findAll(); // returns an object array
$students =  \Webuntis\Serializer\Serializer::serialize($students, 'json|xml|yml') // turn the object array into an array,
                                                                                   // if the second parameter is empty it will return an php array
```


