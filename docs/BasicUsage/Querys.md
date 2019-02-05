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
```php
$query->get('Period')->findAll(sort, limit, options);
//options is a array and can contain these values for filtering
// - options a parameter object encapsulating the following fields:
//      - element id object encapsulating the following fields:
//          - id element id, either the internal id, name, or external key of an element (see „keyType“), mandatory
//           - type element type, mandatory 1 = klasse, 2 = teacher, 3 = subject, 4 = room, 5 = student
//      - keyType „id“, „name“, or„externalkey“, optional (default: „id“)
//      - startDate number, format: YYYYMMDD, optional (default: current date)
//      - endDate number, format: YYYYMMDD, optional (default: current date)
//      - onlyBaseTimetable boolean, returns only the base timetable (without bookings etc.) (default:false)
//      - showBooking boolean, returns the period's booking info if available (default: false)
//      - showInfo boolean, returns the period information if available (default: false)
//      - showSubstText boolean, returns the Untis substitution text if available (default: false)
//      - showLsText boolean, returns the text of the period's lesson (default: false)
//      - showLsNumber boolean, returns the number of the period's lesson (default: false)
//      - showStudentgroup boolean, returns the name(s) of the studentgroup(s) (default: false)
//      - klasseFields array, optional, values: „id“, „name“, „longname“, „externalkey“
//      - roomFields array, optional, values: „id“, „name“, „longname“, „externalkey“
//      - subjectFields array, optional, values: „id“, „name“, „longname“, „externalkey“
//      - teacherFields array, optional, values: „id“, „name“, „longname“, „externalkey“
```
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