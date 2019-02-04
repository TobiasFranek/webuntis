# Configuration

This guide shows you have to set the right configuration.
The basic config array looks like this, but with this configuration you only can access data that needs no right to access.

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

if you want to change the path scheme, for example if your domain is not webuntis.com you can use this config. The keywords {school} and {server} will be replaced with the server name and school, you can also leave them out if you don't have such a thing. In summary you can build your own domain/url.

```php
 'default' => [
        //f.e. thalia, cissa etc.
        'server' => 'yourserver',
        'school' => 'yourschool',
        'username' => 'yourusername',
        'password' => 'yourpassword',
        //this is the default path scheme
        'path_scheme' => 'https://{server}.webuntis.com/WebUntis/jsonrpc.do?school={school}'
    ],
  'admin' => [
        //f.e. thalia, cissa etc.
        'server' => 'yourserver',
        'school' => 'yourschool',
        'username' => 'youradminusername',
        'password' => 'youradminpassword',
        //this is the default path scheme
        'path_scheme' => 'https://{server}.webuntis.com/WebUntis/jsonrpc.do?school={school}'
    ]
```

if you want to turn off caching (which is not recommended) take this configuration:

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
    ],
    'disable_cache' => true
```

if you don't want the default memcached(port=11211, host=localhost) server use:

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
    ],
    'cache' => [
        'type' => 'memcached',
        'host' => 'yourhost',
        'port' => 'yourport
    ]
```

if you want to execute all models with the admin instance
```php
  'admin' => [
        //f.e. thalia, cissa etc.
        'server' => 'yourserver',
        'school' => 'yourschool',
        'username' => 'youradminusername',
        'password' => 'youradminpassword'
    ],
    'only_admin' => true
```

if you have changed the vendor dir in the composer.json config you can add this parameter
```php
  'admin' => [
        //f.e. thalia, cissa etc.
        'server' => 'yourserver',
        'school' => 'yourschool',
        'username' => 'youradminusername',
        'password' => 'youradminpassword'
    ],
    'vendor_dir' => 'lib'
```

if you want to use another SecurityManager you can use this config. With an custom Security Manager you can return your own Client. You just have to implement the Webuntis\Security\Interfaces\SecurityManagerInterface and create the method you need (getClient, getCurrentUserId, getCurrentUserType) and return the right things according to the method. With the method getClient() you can return your own client which is then used by the ExecutionHandler to execute http request to apis, the client class must have an call method. F.e. you could return your own Client thats accesses an complietly differently api (not webuntis) and use this library in an complettly other way. 
```php
  'admin' => [
        //f.e. thalia, cissa etc.
        'server' => 'yourserver',
        'school' => 'yourschool',
        'username' => 'youradminusername',
        'password' => 'youradminpassword'
    ],
    'security_manager' => 'Your\Namespace\Manager'
```

Often you have to request api endpoint with normal users for example Periods, but there is the Problem that the childs that you have in the Periods Model like Teachers and Students need admin access, the problem that arises from this is that you get your access denied. With the option ignore_children you can disable the rendering of the children, so you don't need to access there end points too. just add the option to the instance you want it to work on.

```php
use Webuntis\Configuration\WebuntisConfiguration;

$config = new WebuntisConfiguration([ 
'default' => [
       //f.e. thalia, cissa etc.
        'server' => 'yourserver',
        'school' => 'yourschool',
        'username' => 'yourusername',
        'password' => 'yourpassword',
        'ignore_children' => true
    ],
    'admin' => [
       //f.e. thalia, cissa etc.
        'server' => 'yourserver',
        'school' => 'yourschool',
        'username' => 'youradminusername',
        'password' => 'youradminpassword'
    ]
 ]);
```

At the moment there is a problem with webuntis that all access right have changed, so your account could be found in the Students Repository but has not the UserType 5. There is now an option per instance to define a default User Repository, where the User will be searched for when you login. When you don't define this property and you have a UserType other then 2 or 5 you will get returned the Webuntis\Models\Account class, which has minimal definition (userId, userType).


```php
use Webuntis\Configuration\WebuntisConfiguration;

$config = new WebuntisConfiguration([ 
'default' => [
       //f.e. thalia, cissa etc.
        'server' => 'yourserver',
        'school' => 'yourschool',
        'username' => 'yourusername',
        'password' => 'yourpassword',

        // when calling ->getCurrentUser() the user will be 
        // searched in the Students repository even if the returned UserType is a Teacher or other
        // $query->get('Students')->findBy(['id' => $this->currentUserId])[0];
        'user_type' => 'Students'
    ],
    'admin' => [
       //f.e. thalia, cissa etc.
        'server' => 'yourserver',
        'school' => 'yourschool',
        'username' => 'youradminusername',
        'password' => 'youradminpassword'
    ]
 ]);
```


To apply the configuration you have to simply create a new WebuntisConfiguration object.

```php
use Webuntis\Configuration\WebuntisConfiguration;

$config = new WebuntisConfiguration([ 
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
 ]);
```
# Querys 

Now to the important part, the data fetching.

You just have to create a new Query object.

```php
use Webuntis\Query\Query;

$query = new Query();
```

if you call
```php
$query->get('Students');
```
you get an Repository object which has already defined functions that allow you to fetch the data from the api. the get Parameter is always the model which gets the data from a certain api method. 

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
you can compare dates also:

```php
$query->get('Period')->findBy(['startDate' => '<Y-m-d']);
```

The < is how you want to compare the date < for <= and > for >=:

possible formats:
* Y-m-d H:i
* Y-m-d

You can sort the given output.

```php

$query->get('Exams')->findAll(['startDate' => 'ASC|DESC']);

//you can sort by properties that are in objects that the main objects contains, but that is restricted to one level
$query->get('Exams')->findAll(['teachers:firstName' => 'ASC|DESC']);
```

This will either order the model descending (DESC) or ascending (ASC).

You can now give a limit to the query.
```php

$query->get('Exams')->findAll(['startDate' => 'ASC|DESC'], 5);
```

### Custom Repositories

There are six custom Repositories in the core already and they are the

* PeriodRepository only has some additional parameters to the standard methods.
* Exams has some different logic in the findAll() method.
* ClassHasTeachers has some different logic in the findAll() method.
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

* SchoolyearsRepository has one additional method, which allows you to fetch the current schoolyear:

```php
$query->get('Schoolyears')->getCurrentSchoolyear()
```

* ClassRegEventsRespository needs some additional madatory parameters
```php
$query->get('ClassRegEvents')->findAll([], null, startDate, endDate, element);
// element is a option that the api offers to filter the class reg events it should loog like this
// - element
//     - id id of the element, e.g. „Tobermory“
//     - type type of the element, 1 = klasse, 5 = student
//     - keyType „id“, „name“, or „externalkey“, (default: „id“)
```

* LastImportTimeRepository just has a different parse method, because the returned data from the api is not an array.

* StatusDataRepository again just a different parse method.

### Model Usage

These are all the models that exists in the core build:

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
* Schoolyears - api method: getSchoolyears/getCurrentSchoolyear
* ExamTypes - api method: getExamTypes
* TimegridUnits - api method: getTimegridUnits
* ClassRegEvents - api method: getClassregEvents
* StatusData - api method: getStatusData
* LastImportTime - api method: getLatestImportTime

### Serializer

all the Repository method return an array of Model objects. If you want to serialize the Object you only need to call the serialize() method on an object, this method then return an array:

```php
$user = $query->get('User')->getCurrentUser(); // returns an object
$user = $user->serialize(); // turn the object into an array
```

If you want an other format(supported: json, xml, yml) you can to write this:

```php
$user = $query->get('User')->getCurrentUser(); // returns an object
$user = $user->serialize('json|xml|yml'); // turn the object into one of these formats
```

If you have an array of objects you can serialize the array including the models that are in there, you have to call the Serializer class:

```php
$students = $query->get('Students')->findAll(); // returns an object array
$students =  \Webuntis\Serializer\Serializer::serialize($students, 'json|xml|yml') // turn the object array into an array,
                                                                                   // if the second parameter is empty it will return an php array
```

### original API data

If you want to get the originally received API data from the json rpc end point, call the 

```php
$students = $query->get('Students')->findAll()[0]->getAttributes(); // returns the original received array
```