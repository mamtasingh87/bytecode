<div class="box">
    <div class="heading">
        <h1><img alt="" src="<?php echo theme_url('assets/images/category.png'); ?>">Details </h1>
    </div>
    <div class="content">
        <table class="list">
            <tr>
                <th>Name:</th>
                <td><?php echo isset($data['client_first_name']) ? $data['client_first_name'] : '' . ' ' . (isset($data['client_middle_name']) ? $data['client_middle_name'] : '') . ' ' . isset($data['client_last_name']) ? $data['client_last_name'] : ''  ?></td>
            </tr>
            <tr>
                <th>Date of birth:</th>
                <td><?php echo isset($data['client_dob']) ? date(DATE_FORMAT, strtotime($data['client_dob'])) : ''; ?></td>
            </tr>
            <tr>
                <th>Email</th>
                <td><?php echo isset($data['client_email']) ? $data['client_email'] : ''; ?></td>
            </tr>
            <tr>
                <th>Phone Number</th>
                <td><?php echo isset($data['client_phone']) ? $data['client_phone'] : ''; ?></td>
            </tr>
            <tr>
                <th>Street Address</th>
                <td><?php echo isset($data['street_address']) ? $data['street_address'] : ''; ?></td>
            </tr>
            <tr>
                <th>Apt</th>
                <td><?php echo isset($data['apt']) ? $data['apt'] : ''; ?></td>
            </tr>
            <tr>
                <th>State</th>
                <td><?php echo isset($data['state']) ? $states[$data['state']] : ''; ?></td>
            </tr>
            <tr>
                <th>City</th>
                <td><?php echo isset($data['city']) ? $data['city'] : ''; ?></td>
            </tr>
            <tr>
                <th>Zip Code</th>
                <td><?php echo isset($data['zip_code']) ? $data['zip_code'] : ''; ?></td>
            </tr>
            <tr>
                <th>Occupancy</th>
                <td><?php echo isset($data['occupancy']) ? $data['occupancy'] : ''; ?></td>
            </tr>
            <tr>
                <th>Effective Date</th>
                <td><?php echo isset($data['effective_date']) ? $data['effective_date'] : ''; ?></td>
            </tr>
            <tr>
                <th>Year Built</th>
                <td><?php echo isset($data['year_built']) ? $data['year_built'] : ''; ?></td>
            </tr>
            <tr>
                <th>Square feet</th>
                <td><?php echo isset($data['square_feet']) ? $data['square_feet'] : ''; ?></td>
            </tr>
            <tr>
                <th>Construction</th>
                <td><?php echo isset($data['construction']) ? $data['construction'] : ''; ?></td>
            </tr>
            <tr>
                <th>Street</th>
                <td><?php echo isset($data['street_address']) ? $data['street_address'] : ''; ?></td>
            </tr>
            <tr>
                <th>Transaction Type</th>
                <td><?php echo isset($data['transaction_type']) ? $data['transaction_type'] : ''; ?></td>
            </tr>
            <tr>
                <th>Policy type</th>
                <td><?php echo isset($data['policy_type']) ? $data['policy_type'] : ''; ?></td>
            </tr>
            <tr>
                <th>Ownership Type</th>
                <td><?php echo isset($data['ownership_type']) ? $data['ownership_type'] : ''; ?></td>
            </tr>
            <tr>
                <th>Fore Closure</th>
                <td><?php echo (isset($data['is_foreclosure']) ? $data['is_foreclosure'] : 0) ? $data['foreclosure'] : 'No'; ?></td>
            </tr>
            <tr>
                <th>Bankruptcy</th>
                <td><?php echo (isset($data['is_bankruptcy']) ? $data['is_bankruptcy'] : 0) ? $data['bankruptcy'] : 'No'; ?></td>
            </tr>
            <tr>
                <th>Bank Owned </th>
                <td><?php echo (isset($data['is_bank_owned']) ? $data['is_bank_owned'] : 0) ? $data['bankruptcy'] : 'No'; ?></td>
            </tr>
            <tr>
                <th>Desired Coverage Amount</th>
                <td><?php echo isset($data['desired_coverage_amount']) ? $data['desired_coverage_amount'] : ''; ?></td>
            </tr>
            <tr>
                <th>Food Zone Determination</th>
                <td><?php echo isset($data['is_flood_zone']) ? 'Yes' : 'No'; ?></td>
            </tr>
            <tr>
                <th>Quote Information</th>
                <td><?php echo isset($data['quote_information']) ? $data['quote_information'] : ''; ?></td>
            </tr>
            <tr>
                <th>Name </th>
                <td><?php echo isset($data['quote_information']) ? $data['quote_information'] : ''; ?></td>
            </tr>
            <tr>
                <th>Quote</th>
                <td><?php echo isset($data['name']) ? $data['name'] : ''; ?></td>
            </tr>
            <tr>
                <th>Email</th>
                <td><?php echo isset($data['email']) ? $data['email'] : ''; ?></td>
            </tr>
            <tr>
                <th>Phone Number</th>
                <td><?php echo isset($data['phone_no']) ? $data['phone_no'] : ''; ?></td>
            </tr>
        </table>
        <div>
            <fieldset>
                <?php if ($quotefiles) { ?>
                    <legend>Attachments</legend>
                    <table>
                        <tr>
                            <td>&nbsp;</td>
                            <td><a title="My account" href="<?php echo site_url('users/account/download/quote/' . $data['id']); ?>" class="btn btn-primary"><i class="fa fa-download"></i> Download All </a></td>
                        </tr>
                        <tr>
                            <?php
                            $count = count($quotefiles);
                            for ($i = 0; $i < $count; $i++) {
                                ?>
                                <td><?php echo $quotefiles[$i]->file_name; ?></td><td><a title="My account" href="<?php echo site_url('users/account/download/quote/' . $quotefiles[$i]->file_name); ?>" class="btn btn-primary"><i class="fa fa-download"></i> Download </a></td>
                            </tr>            
    <?php }
} ?>
                </table>   
            </fieldset>
        </div>

    </div>
</div>
