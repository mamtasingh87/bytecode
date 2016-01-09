<h2 class="sub-inner-head">Invitation Log</h2>

    <div class="box">
                <div class="box-header">
                  
                  <div class="box-tools">
                      <?php echo form_open('/users/account/invitation-log');?>
                    <div class="input-group">
                        <input type="text" placeholder="Search" class="form-control input-sm pull-right" name="table_search" value="<?php echo ($search!="")?$search:"";?>">
                      <div class="input-group-btn">
                          <button class="btn btn-sm btn-default" name="search" type="submit"><i class="fa fa-search"></i></button>
                      </div>
                    </div>
                      <?php echo form_close();?>
                  </div>
                </div><!-- /.box-header -->
                <div class="box-body table-responsive">
                  <table class="table table-hover">
                    <tbody><tr>
                      <th>Email</th>
                      <th>Points</th>
                      <th>Requests Sent</th>
                      <th>Status</th>
                    </tr>
                    <?php if(!empty($log)) { 
                             foreach($log as $value) { ?>
                    <tr>
                      <td><?php echo $value->email_id;?></td>
                      <td><?php echo ($value->activated==1)?$value->points:"0.00";?></td>
                      <td><?php echo $value->requests_sent;?></td>
                      <td><?php echo $status[$value->activated];?></td>
                    </tr>
                
                    <?php } } else { ?>
                    <tr><td colspan="6">No records were found in our database.</td></tr>
                             <?php } ?>
                  </tbody></table>
                    <div class="pagination">
            <div class="links"><?php echo $pagination->create_links(); ?></div>
            <div class="results">Showing <?php echo ($log) ? $limit + 1 : 0; ?> to <?php echo $limit + count($log); ?> of <?php echo $total; ?> (<?php echo $no_pages; ?>  Pages)</div>
        </div>
                </div>
              </div>