<h2 class="sub-inner-head">Dashboard</h2>
<div class="dashboard-wrap">
    
    <div class="row">
        
        <div class="col-sm-3">
            <div class="info">
                <a href="<?php echo site_url('users/account/profile'); ?>">
            <span class="icon-span"><i class="fa fa-user"></i></span>
            <h4>Profile</h4>
                </a>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="info">
                <a href="<?php echo site_url('users/account/change-password'); ?>">
            <span class="icon-span"><i class="fa fa-key"></i></span>
            <h4>Change Password</h4>
                </a>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="info">
                <a href="<?php echo site_url('users/account/rewards'); ?>">
            <span class="icon-span"><i class="fa fa-star"></i></span>
            <h4>Rewards</h4>
                </a>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="info">
                <a href="<?php echo site_url('quote_request'); ?>">
            <span class="icon-span"><i class="fa fa-pencil-square"></i></span>
            <h4>New Quote Request</h4>
                </a>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="info">
                <a href="<?php echo site_url('users/account/previous-quotes'); ?>">
            <span class="icon-span"><i class="fa fa-pencil-square"></i></span>
            <h4>Previous Quote Requests</h4>
                </a>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="info">
                <a href="<?php echo site_url('binder_request'); ?>">
            <span class="icon-span"><i class="fa fa-pencil-square"></i></span>
            <h4>New Binder Request</h4>
                </a>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="info">
                <a href="<?php echo site_url('users/account/previous-binders'); ?>">
            <span class="icon-span"><i class="fa fa-pencil-square"></i></span>
            <h4>Previous Binder Requests</h4>
                </a>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="info">
                <a href="<?php echo site_url('/users/account/invitation-log'); ?>">
            <span class="icon-span"><i class="fa fa-pencil-square"></i></span>
            <h4>Invitation Log</h4>
                </a>
            </div>
        </div>
       
    </div>
    
    
<!--    <div class="box box-primary">
                <h2 class="sub-inner-head"><i class="fa fa-bar-chart-o"></i> Current Year Points Statistics</h2>
                <div class="box-body">
                  <div id="bar-chart" style="height: 300px; ">
</div>
                </div> /.box-body
    </div>-->
             

<script type="text/javascript">
    $(function () {
    /* END AREA CHART */

        /*
         * BAR CHART
         * ---------
         */

        var bar_data = {
          data: [<?php echo $chart;?>],
          color: "#95b21d"
        };
        $.plot("#bar-chart", [bar_data], {
          grid: {
            borderWidth: 1,
            borderColor: "#f3f3f3",
            tickColor: "#f3f3f3"
          },
          series: {
            bars: {
              show: true,
              barWidth: 0.5,
              align: "center"
            }
          },
          xaxis: {
            mode: "categories",
            tickLength: 0
          }
        });
        /* END BAR CHART */
    });
    </script>
</div>