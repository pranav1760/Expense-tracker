<?php
session_start(); // Start session to store income and expenses

// Initialize session variables for income and expenses if they don't exist
if (!isset($_SESSION['expenses'])) {
  $_SESSION['expenses'] = array();
}
if (!isset($_SESSION['income'])) {
  $_SESSION['income'] = array();
}

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Add a new expense
  if (isset($_POST['add_expense'])) {
    $expense_name = trim($_POST['expense_name']);
    $expense_amount = trim($_POST['expense_amount']);
    if (!empty($expense_name) && is_numeric($expense_amount) && $expense_amount > 0) {
      $_SESSION['expenses'][] = ['name' => $expense_name, 'amount' => $expense_amount];
    }
  }
  // Add a new income
  elseif (isset($_POST['add_income'])) {
    $income_name = trim($_POST['income_name']);
    $income_amount = trim($_POST['income_amount']);
    if (!empty($income_name) && is_numeric($income_amount) && $income_amount > 0) {
      $_SESSION['income'][] = ['name' => $income_name, 'amount' => $income_amount];
    }
  }
  // Delete an expense
  elseif (isset($_POST['delete_expense'])) {
    $index = $_POST['index'];
    if (isset($_SESSION['expenses'][$index])) {
      unset($_SESSION['expenses'][$index]);
      $_SESSION['expenses'] = array_values($_SESSION['expenses']); // Re-index array
    }
  }
  // Delete an income
  elseif (isset($_POST['delete_income'])) {
    $index = $_POST['index'];
    if (isset($_SESSION['income'][$index])) {
      unset($_SESSION['income'][$index]);
      $_SESSION['income'] = array_values($_SESSION['income']); // Re-index array
    }
  }
}

function calculate_total($items) {
  $total = 0;
  foreach ($items as $item) {
    $total += $item['amount'];
  }
  return $total;
}

$total_income = calculate_total($_SESSION['income']);
$total_expenses = calculate_total($_SESSION['expenses']);
$balance = $total_income - $total_expenses;
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Expense Tracker</title>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&family=Open+Sans:wght@400;700&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Roboto', sans-serif;
      margin: 0;
      padding: 0;
      background: url('https://as1.ftcdn.net/v2/jpg/01/84/00/40/1000_F_184004064_Fz2JAX80jiUIT0AZARocVjhsXcF3TaPY.jpg') no-repeat center center fixed;
      background-size: cover;
    }

    .container {
      width: 90%;
      max-width: 600px;
      margin: 50px auto;
      padding: 30px;
      border-radius: 10px;
      background-color: rgba(255, 255, 255, 0.9);
      box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
    }

    h1 {
      font-family: 'Open Sans', sans-serif;
      text-align: center;
      font-size: 28px;
      margin-bottom: 20px;
      color: #2c3e50;
    }

    .item-list {
      list-style: none;
      padding: 0;
      margin: 0;
    }

    .item-list li {
      margin-bottom: 15px;
      padding: 10px;
      border-radius: 5px;
      background-color: #ecf0f1;
      transition: background-color 0.3s ease;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .item-list li:hover {
      background-color: #dfe6e9;
    }

    .item-list li .name {
      font-weight: bold;
      color: #2c3e50;
    }

    .item-list li .amount {
      color: #2c3e50;
    }

    .item-list li form {
      display: inline;
      margin-left: 10px;
    }

    .error {
      color: red;
      text-align: center;
      margin-bottom: 10px;
    }

    input[type="text"], input[type="number"] {
      width: calc(100% - 100px);
      padding: 10px;
      border: 1px solid #bdc3c7;
      border-radius: 5px;
      font-size: 16px;
      margin-bottom: 10px;
    }

    button {
      padding: 10px 15px;
      border: none;
      border-radius: 5px;
      background-color: #3498db;
      color: #fff;
      font-size: 16px;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }

    button:hover {
      background-color: #2980b9;
    }

    .summary {
      text-align: center;
      margin-top: 20px;
    }

    .summary .total-income, .summary .total-expenses, .summary .balance {
      font-size: 20px;
      margin-bottom: 10px;
    }

    .total-income {
      color: green;
    }

    .total-expenses {
      color: red;
    }

    .balance {
      color: #2c3e50;
    }

    @media (max-width: 600px) {
      .container {
        width: 100%;
        padding: 15px;
      }

      input[type="text"], input[type="number"] {
        width: calc(100% - 90px);
      }
    }
  </style>
</head>
<body>
  <div class="container">
    <h1>Expense Tracker</h1>

    <form action="" method="post">
      <input type="text" name="expense_name" placeholder="Enter expense name" required>
      <input type="number" name="expense_amount" placeholder="Enter amount" required>
      <button type="submit" name="add_expense">Add Expense</button>
    </form>

    <ul class="item-list">
      <?php foreach ($_SESSION['expenses'] as $index => $expense): ?>
        <li>
          <div class="name"><?php echo htmlspecialchars($expense['name']); ?></div>
          <div class="amount"><?php echo htmlspecialchars($expense['amount']); ?></div>
          <form action="" method="post" style="display: inline;">
            <input type="hidden" name="index" value="<?php echo $index; ?>">
            <button type="submit" name="delete_expense">Delete</button>
          </form>
        </li>
      <?php endforeach; ?>
    </ul>

    <form action="" method="post">
      <input type="text" name="income_name" placeholder="Enter income source" required>
      <input type="number" name="income_amount" placeholder="Enter amount" required>
      <button type="submit" name="add_income">Add Income</button>
    </form>

    <ul class="item-list">
      <?php foreach ($_SESSION['income'] as $index => $income): ?>
        <li>
          <div class="name"><?php echo htmlspecialchars($income['name']); ?></div>
          <div class="amount"><?php echo htmlspecialchars($income['amount']); ?></div>
          <form action="" method="post" style="display: inline;">
            <input type="hidden" name="index" value="<?php echo $index; ?>">
            <button type="submit" name="delete_income">Delete</button>
          </form>
        </li>
      <?php endforeach; ?>
    </ul>

    <div class="summary">
      <div class="total-income">Total Income: $<?php echo number_format($total_income, 2); ?></div>
      <div class="total-expenses">Total Expenses: $<?php echo number_format($total_expenses, 2); ?></div>
      <div class="balance">Balance: $<?php echo number_format($balance, 2); ?></div>
    </div>
  </div>
</body>
</html>
