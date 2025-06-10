<h2>Add Employee</h2>
<form method="POST">
    <label>Name:</label><input type="text" name="name" required><br>
    <label>Email:</label><input type="email" name="email" required><br>
    <label>Department:</label>
    <select name="department">
        <?php foreach (\$data['departments'] as \$dept): ?>
            <option value="<?= \$dept['id'] ?>"><?= \$dept['name'] ?></option>
        <?php endforeach; ?>
    </select><br>
    <button type="submit">Add</button>
</form>