<?php
// auto-generated by sfPropelCrud
// date: 2008/02/20 12:05:38

weflowTools::printR($ull_users);

//$gen = new sfPropelCrudGenerator();




?>
<h1>ullUser</h1>

<table>
<thead>
<tr>
  <th>Id</th>
  <th>First name</th>
  <th>Last name</th>
  <th>Email</th>
  <th>Username</th>
  <th>Password</th>
  <th>Location</th>
  <th>User type</th>
  <th>Creator user</th>
  <th>Created at</th>
  <th>Updator user</th>
  <th>Updated at</th>
</tr>
</thead>
<tbody>
<?php foreach ($ull_users as $ull_user): ?>
<tr>
    <td><?php echo link_to($ull_user->getId(), 'ullUser/show?id='.$ull_user->getId()) ?></td>
      <td><?php echo $ull_user->getFirstName() ?></td>
      <td><?php echo $ull_user->getLastName() ?></td>
      <td><?php echo $ull_user->getEmail() ?></td>
      <td><?php echo $ull_user->getUsername() ?></td>
      <td><?php echo $ull_user->getPassword() ?></td>
      <td><?php echo $ull_user->getLocationId() ?></td>
      <td><?php echo $ull_user->getUserType() ?></td>
      <td><?php echo $ull_user->getCreatorUserId() ?></td>
      <td><?php echo $ull_user->getCreatedAt() ?></td>
      <td><?php echo $ull_user->getUpdatorUserId() ?></td>
      <td><?php echo $ull_user->getUpdatedAt() ?></td>
  </tr>
<?php endforeach; ?>
</tbody>
</table>

<?php echo link_to ('create', 'ullUser/create') ?>
