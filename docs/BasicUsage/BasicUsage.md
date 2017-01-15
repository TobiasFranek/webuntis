# Configuration

This guide shows you have to set the right configuration.
The basic config array looks like this, but with this configuration you only can access data that need no right to access, like students or teachers

```php
 'default' => [
       //f.e. thalia, cissa etc.
        'server' => 'yourserver',
        'school' => 'yourschool',
        'username' => 'yourusername',
        'password' => 'yourpassword'
        ]
```

The recommended configuration is when you have an default and an admin configuration, so you can fetch all data from the api. The admin account doesn't have to be an admin account it is enough when you give him full reading permission.

```php
 'default' => [
        //f.e. thalia, cissa etc.
        'server' => 'yourserver',
        'school' => 'yourschool',
        'username' => 'yourusername',
        'password' => 'yourpassword'
    ],
  'admin' => [
        //f.e. thalia, cissa etc.
        'server' => 'yourserver',
        'school' => 'yourschool',
        'username' => 'youradminusername',
        'password' => 'youradminpassword'
    ]
```

To apply the configuration you have to simply create a new WebuntisConfiguration object.

```php
$config = new WebuntisConfiguration( 
'default' => [
       //f.e. thalia, cissa etc.
        'server' => 'yourserver',
        'school' => 'yourschool',
        'username' => 'yourusername',
        'password' => 'yourpassword'
    ],
'admin' => [
       //f.e. thalia, cissa etc.
        'server' => 'yourserver',
        'school' => 'yourschool',
        'username' => 'youradminusername',
        'password' => 'youradminpassword'
    ]
  )
```

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

you also can sort the given output

$query->get('Exams')->findAll(['startDate' => 'ASC|DESC']);

this will either order the model descending (DESC) or ascending (ASC)

you also can now give a limit to the query

$query->get('Exams')->findAll(['startDate' => 'ASC|DESC'], 5);

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



