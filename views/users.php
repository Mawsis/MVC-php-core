<h1>User List</h1>
<table border="1" width="100%" cellpadding="8" cellspacing="0">
    <thead>
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Email</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($users as $user): ?>
        <tr>
            <td><?= $user->id ?></td>
            <td><?= $user->username ?></td>
            <td><?= $user->email ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php include __DIR__ . "/components/pagination.php"; ?>