<?php

session_start();
error_reporting(0);
include('includes/dbcon.php');

?>
<div class=" row card-body">
  <?php
  $eid2=$_POST['edit_id2'];
  $ret2=mysqli_query($con,"select * from  facmembers where mem_id='$eid2'");
  while ($row=mysqli_fetch_array($ret2))
  {
    ?> 
    <div class="col-md-4">
      <img src="facimages/<?php echo htmlentities($row['facImage']);?>" width="100" height="100">
    </div>
    <div class="col-md-8">
      <table>
         <tr>
          <th>Employee Number</th>
          <td>&nbsp;<?php  echo $row['emp_no'];?></td>
        </tr>
        <tr>
          <th>Name</th>
          <td><?php  echo $row['emp_name'];?></td>
        </tr>
        <tr>
          <th>Status</th>
          <td><?php  echo $row['status'];?></td>
        </tr>
        <tr>
          <th>Contact No.</th>
          <td><?php  echo $row['cp'];?></td>
        </tr>
      </table>
    </div>
    <?php 
  } ?>
</div>