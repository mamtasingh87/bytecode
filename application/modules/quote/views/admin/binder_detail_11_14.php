<div class="box">
    <div class="heading">
        <h1>Binder Detail [<?php echo isset($data['borrower_name'])?$data['borrower_name']:'';?>] </h1>
    </div>
    <div class="content">
        <table class="list">
            <tbody>
                <tr>
                    <td>Name</td>
                    <td><?php echo isset($data['borrower_name'])?$data['borrower_name']:'';?></td>
                </tr>
                <tr>
                    <td>Email</td>
                    <td><?php echo isset($data['borrower_email'])?$data['borrower_email']:'';?></td>
                </tr>
                <tr>
                    <td>Phone</td>
                    <td><?php echo isset($data['borrower_phone'])?$data['borrower_phone']:'';?></td>
                </tr>
                <tr>
                    <td>Quote Amount to be Bound</td>
                    <td><?php echo isset($data['premium_quote'])?$data['premium_quote']:'';?></td>
                </tr>
                <tr>
                    <td>Mortgagee Clause</td>
                    <td><?php echo isset($data['mortgage_clause'])?$data['mortgage_clause']:'';?></td>
                </tr>
                <tr>
                    <td>Closing Date</td>
                    <td><?php echo isset($data['closing_date'])?$data['closing_date']:'';?></td>
                </tr>
                <tr>
                    <td>Loan Number</td>
                    <td><?php echo isset($data['loan_number'])?$data['loan_number']:'';?></td>
                </tr>
                <tr>
                    <td>Requested By</td>
                    <td><?php echo isset($data['first_name'])?$data['first_name'].' '.(isset($data['last_name'])?$data['last_name']:'') :'';?></td>
                </tr>
                <tr>
                    <td>Requested on</td>
                    <td><?php echo isset($data['requested_on'])?$data['requested_on']:'';?></td>
                </tr>
                <tr>
                    <td>Status</td>
                    <td><?php echo isset($status[$data['status']]) ? $status[$data['status']] : 'NA'; ?></td>
                </tr>
                <?php if ($data['requested_document']) { ?>
                <tr>
                    <td>Attached Document</td>
                    <td><a title="My account" href="<?php echo site_url('users/account/download/binder/'.$data['requested_document']);?>" class="btn btn-primary"><i class="fa fa-download"></i> Download </a></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

