<?php

class Employee extends AbstractModel
{
  private  $name;
  private  $id;
  private  $age;
  private  $address;
  private  $salary;
  private  $tax;

  protected static $tableName = 'employees';
  protected static $tableSchema = [
    'name'    => self::DATA_TYPE_STR,
    'age'     => self::DATA_TYPE_INT,
    'address' => self::DATA_TYPE_STR,
    'salary'  => self::DATA_TYPE_DECIMAL,
    'tax'     => self::DATA_TYPE_DECIMAL
  ];
  protected static $primaryKey = 'id';

  public function __construct($name, $age, $address, $salary, $tax)
  {
    $this->name = $name;
    $this->age = $age;
    $this->address = $address;
    $this->salary = $salary;
    $this->tax = $tax;
  }

  public function __get($prop)
  {
    return $this->$prop;
  }

  public function __set($prop, $value) {
    $this->$prop = $value;
  }


  public function calculateSalary() {
    return $this->salary - ($this->salary * ($this->tax / 100));
  }

  public function getTableName() {
    return self::$tableName;
  }

}