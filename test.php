<?php
require_once "./db.php";
require_once "./classes/AbstractModel.php";
require_once "./classes/Employee.php";

$emps = Employee::get("SELECT * FROM employees WHERE age BETWEEN :age1 AND :age2", [
  "age1" => [
    Employee::DATA_TYPE_INT,
    22
  ],
  "age2" => [
    Employee::DATA_TYPE_INT,
    24
  ]
]);

echo "<pre>";
var_dump($emps);
echo "</pre>";
