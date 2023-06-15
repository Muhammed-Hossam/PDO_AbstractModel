<?php
session_start();
require_once "./db.php";
require_once "./classes/AbstractModel.php";
require_once "./classes/Employee.php";


if (isset($_POST['submit'])) {
  $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
  $age = intval($_POST['age']);
  $address = filter_input(INPUT_POST, 'address', FILTER_SANITIZE_STRING);
  $salary = floatval($_POST['salary']);
  $tax = floatval($_POST['tax']);



  if ($name && $age && $address && $salary && $tax) {

    // Insert into or Updating employees database
    if (isset($_GET['action']) && $_GET['action'] === 'edit' && isset($_GET['id'])) {
      $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
      if ($id > 0) {
        $user = Employee::getByPK($id);
        $user->name = $name;
        $user->age = $age;
        $user->address = $address;
        $user->salary = $salary;
        $user->tax = $tax;
      }
    } else {
      $user = new Employee($name, $age, $address, $salary, $tax);
    }


    if ($user->save() === true) {
      $_SESSION['message'] = "Employee Saved Successfully!";
      header("Location: http://localhost/php_pdo");
      session_write_close();
      exit;
    } else {
      $_SESSION['message'] = "Error Saving employee $name";
    }
  } else {
    $message = "Invalid input Values";
  }

}

// Update employee
if (isset($_GET['action']) && $_GET['action'] === 'edit' && isset($_GET['id'])) {
  $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
  if ($id > 0) {
    $user = Employee::getByPK($id);
  }
}

// Delete employee data
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
  $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
  if ($id > 0) {
    $user = Employee::getByPK($id);
    if ($user->delete() === true) {
      $_SESSION['message'] = "The Employee Deleted Successfully!";
      header("Location: http://localhost/php_pdo");
      session_write_close();
      exit;
    }
  }
}

// Retrive Employees Data from database
$result = Employee::getAll();

?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>PHP PDO</title>
  <style>
    .employees table,
    .employees th,
    .employees td {
      border: 2px solid black;

    }

    .employees th,
    .employees td {
      padding: 1rem;
      text-align: center;
    }

    .controls a {
      margin: 0.5rem;

    }
  </style>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css"
    integrity="sha512-t4GWSVZO1eC8BM339Xd7Uphw5s17a86tIZIj8qRxhnKub6WoyhnrxeCIMeAqBPgdZGlCcG2PrZjMc+Wr78+5Xg=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
    integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>
  <form method="post" enctype="application/x-www-form-urlencoded" class="p-5">
    <?php if (isset($_SESSION['message'])) { ?>
      <p class="message">
        <?= $_SESSION['message'] ?>
      </p>
      <?php
      unset($_SESSION['message']);
    }
    ?>
    <div class="input-group mb-3">
      <span class="input-group-text" id="addon-wrapping">Name:</span>
      <input type="text" class="form-control" placeholder="Name" name="name" required
        value="<?= isset($user) ? $user->name : '' ?>" />
    </div>
    <div class="input-group mb-3">
      <span class="input-group-text" id="addon-wrapping">Age:</span>
      <input type="number" class="form-control" placeholder="Age" name="age" min="22" max="60" required
        value="<?= isset($user) ? $user->age : '' ?>" />
    </div>
    <div class="input-group mb-3">
      <span class="input-group-text" id="addon-wrapping">Address:</span>
      <input type="text" class="form-control" placeholder="Address" name="address"
        value="<?= isset($user) ? $user->address : '' ?>" />
    </div>
    <div class="input-group mb-3">
      <span class="input-group-text" id="addon-wrapping">Salary:</span>
      <input type="number" class="form-control" placeholder="Salary" name="salary" min="1500" max="900000"
        value="<?= isset($user) ? $user->salary : '' ?>" />
    </div>
    <div class="input-group mb-3">
      <span class="input-group-text" id="addon-wrapping">Tax:</span>
      <input type="number" class="form-control" placeholder="Tax" name="tax" step="0.01" min="1" max="5"
        value="<?= isset($user) ? $user->tax : '' ?>" />
    </div>
    <div class="input-group mb-3">
      <input type="submit" value="Save" name="submit">
    </div>
  </form>

  <div class="employees d-flex justify-content-center align-content-center mb-5">
    <table>
      <thead>
        <tr>
          <th>Name</th>
          <th>Age</th>
          <th>Address</th>
          <th>Salary</th>
          <th>Tax (%)</th>
          <th>Controls</th>
        </tr>
      </thead>
      <tbody>
        <?php
        if ($result !== false) {
          foreach ($result as $employee) {
            ?>
            <tr>
              <td>
                <?= $employee->name; ?>
              </td>
              <td>
                <?= $employee->age; ?>
              </td>
              <td>
                <?= $employee->address; ?>
              </td>
              <td>
                <?= $employee->calculateSalary(); ?> L.E
              </td>
              <td>
                <?= $employee->tax; ?>%
              </td>
              <td class="controls">
                <a href="/php_pdo/?action=edit&id=<?= $employee->id; ?>"><i
                    class="fa-solid fa-pen-to-square fa-lg text-warning"></i></a>
                <a href="/php_pdo/?action=delete&id=<?= $employee->id ?>"><i class="fa-solid fa-trash fa-lg text-danger"
                    onclick="if (!confirm('Do you want to delete this employee')) return false"></i></a>
              </td>
            </tr>
            <?php
          }
        } else {
          ?>
          <td colspan="5">Sorry no Employees to list!</td>
          <?php
        }
        ?>
      </tbody>
    </table>
  </div>



  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.min.js"
    integrity="sha512-3dZ9wIrMMij8rOH7X3kLfXAzwtcHpuYpEgQg1OA4QAob1e81H8ntUQmQm3pBudqIoySO5j0tHN4ENzA6+n2r4w=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</body>

</html>