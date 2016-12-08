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

your also can search recursively like this:

```php
$query->get('Period')->findBy(['teacher:firstName' => 'seppi']);
```
this will return all the Period Models where the teachers have the first name 'seppi'

### Custom Repositories

There are two custom Repositories in the core already and they are the

* PeriodRepository only has some additional parameters to the standard methods:
* StudentsRepository with these additional functions:

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

all the Repository method return the Model so an array of Model objects. If you want to serialize the Object you only need to call the serialize() method on an objects, this method then return an array.

```php 
$student = $query->get('User')->getCurrentUser(); // returns an object
$student = $student->serialize(); // turn the object into an array
```



