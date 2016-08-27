# SQLManager - PHP MySQL Helper

**SQLManager** is a PHP Class to help make interacting with your MySQL database easy. Using the *PHP Data Objects* (PDO) extension, **SQLManager** is an easily reusable class that lets you focus on reading and writing data to your database without having to have PDO initialization code everywhere.

## Setup
All you need to set up **SQLManager** is to set up a *config.ini* file with your database credentials and just drop the *SQLManager.php* file in your project. From there, all you need to do is reference it at the start of any file that's using it. You should probably put your *config.ini* file in a secured folder so it's all safe. And remember to always parameterize your SQL queries!

```php
require_once "SqlManager/SqlManager.php";
```

After that, when you're ready to do some SQL queries, just create a new *SQLManager* object.

```php
$sql = new SQLManager();
````

After that, you're all set! You can now use **SQLManager** to do queries for you.

## Usage

For the following examples, assume there is a table in your database titled **hats** with this structure and contents:

| id | name  | height | color | owner  |
|---:|-------|-------:|-------|--------|
|   1|Bowler |      5"|purple | Bob    |
|   2|Top Hat|    1'3"|black  | Susan  |
|   3|Trilby |      3"|grey   | Bob    |
|   4|Trilby |      4"|grey   | Big Bob|
|   5|Garlic |      2"|white  | Wario  |
|   6|Wig    |      8"|brown  | Bob    |

### Query
The Query function takes a SQL statement and parameterized array as input and returns the data needed to loop through the results. **SQLManager** uses the ```fetch(PDO::FETCH_ASSOC)``` method instead of ```fetchAll()```, so a ```while``` loop is needed.

```php
$sql = new SQLManager();
$table = $sql->Query("SELECT name, height FROM hats WHERE owner = :o", ["o" => "Bob"]);
while($row = $table->fetch(PDO::FETCH_ASSOC)) {
	echo "Bob's hat ".$row["name"]." is ".$row["height"]." tall!";
}
```

This will output:
```
Bob's hat Bowler is 5" tall!
Bob's hat Trilby is 3" tall!
Bob's hat Wig is 8" tall!
```

### QueryRow
The QueryRow function takes a SQL statement and parameterized array as input and returns an associative array.

```php
$sql = new SQLManager();
$row = $sql->QueryRow("SELECT id, name, color FROM hats WHERE id = :i", ["i" => 2]]);
echo "Hat #".$row["id"]." is a ".$row["color"]." ".$row["name"]."!";
```

This will output:
```
Hat #2 is a black Top Hat!
```

### QueryVal
The QueryVal function takes a SQL statement and parameterized array as input and returns a single value.
```php
$sql = new SQLManager();
$color = $sql->QueryVal("SELECT color FROM hats WHERE owner = :o AND name = :n", ["o" => "Big Bob", "n" => "Trilby"]]);
echo "Big Bob's trilby is $color!";
```

This will output:
```
Big Bob's trilby is grey!
```

### QueryCount
QueryCount is the same as QueryVal, but its return is wrapped in ```intval```, so the result will always be an integer type.
```php
$sql = new SQLManager();
$hatCount = $sql->QueryCount("SELECT COUNT(*) FROM hats WHERE owner = :o", ["o" => "Bob"]);
echo "Bob owns $hatCount hats!";
```

This will output:
```
Bob owns 3 hats!
```

### QueryExists
The QueryExists function behaves the same as QueryVal and QueryCount, but returns true or false depending on if the resulting query returned a number above 0 or not.
```php
$sql = new SQLManager();
$hasHat = $sql->QueryExists("SELECT COUNT(*) FROM hats WHERE owner = @o AND color = :c", ["o" => "Susan", "c" => "blue"]);
if($hasHat) {
	echo "Susan has at least one blue hat!";
} else {
	echo "Susan has no blue hats!";
}
```

This will output:
```
Susan owns no blue hats!
```

### GetLastInsertId
This function will return the primary key of the last row inserted into the database using the current **SQLManager** instance.
```php
$sql = new SQLManager();
$sql->Query("INSERT INTO hats (name, height, color, owner) VALUES (:n, :h, :c, :o)", ["n" => "Big Blue Hat", "h" => "5'5\"", "c" => "blue", "o" => "Susan"]);
echo $sql->GetLastInsertId();
```

This will output:
```
7
```

### InsertAndReturn
This function is just a combination of *Query* and *GetLastInsertId* for convenience.
```php
$sql = new SQLManager();
echo $sql->InsertAndReturn("INSERT INTO hats (name, height, color, owner) VALUES (:n, :h, :c, :o)", ["n" => "Big Blue Hat", "h" => "5'5\"", "c" => "blue", "o" => "Susan"]);
```

This will output:
```
7
```

### ErrorLog::AddError
There is an additional Error Log added to this project to write errors to your error log in a consistent way. This function can also be modified so that your errors will be logged to a file or a database table.

```php
ErrorLog::AddError("Test", "This is just a test!");
```

This will output, in your PHP error logs:
```
Test Error: This is just a test!
```

## License
**SQLManager** is licensed [GNU GPLv3](https://www.gnu.org/licenses/gpl-3.0.en.html) because  sharing is caring.


## Who Dares?
**SQLManager** was created by [Sean Finch](http://hauntedbees.com) and  is used in [most of his web-based projects](https://github.com/HauntedBees?tab=repositories).