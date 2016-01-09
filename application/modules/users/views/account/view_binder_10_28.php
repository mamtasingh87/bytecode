<dl>
    <dt>Borrower Name</dt>
    <dd><?php echo $binder->borrower_name;?></dd>
    <dt>Borrower Email</dt>
    <dd><?php echo $binder->borrower_email;?></dd>
    <dt>Borrower Phone</dt>
    <dd><?php echo $binder->borrower_phone;?></dd>
    <dt>Premium Quote</dt>
    <dd><?php echo $binder->premium_quote;?></dd>
    <dt>Closing Date</dt>
    <dd><?php echo $binder->closing_date;?></dd>
    <dt>Status</dt>
    <dd><?php echo "Pending";?></dd>
    <dt>Mortgage Clause</dt>
    <dd><?php echo $binder->mortgage_clause;?></dd>
    <dt>Loan Number</dt>
    <dd><?php echo $binder->loan_number;?></dd>
    <?php if($binder->requested_document) { ?>
    <dt>Attached Document</dt>
    <dd><a title="My account" href="<?php echo site_url('users/account/download/binder/'.$binder->requested_document);?>" class="btn btn-primary"><i class="fa fa-download"></i> Download </a></dd>
    <?php } ?>
</dl>