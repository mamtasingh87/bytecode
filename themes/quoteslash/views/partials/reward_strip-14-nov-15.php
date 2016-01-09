<?php
$usersModel = $this->load->model('users/users_model');
$comData = $usersModel->showUserClubInfo($this->secure->get_user_session()->id);
$redeemPoint = $usersModel->showRedeemPoint($this->secure->get_user_session()->id);
$goldClub = $this->settings->gold_no_referrals_trailing_months;
$silverClub = $this->settings->silver_no_referrals_trailing_months;
$bronzeClub = 0;
if ($comData['total_referral'] >= $silverClub && $comData['total_referral'] < $goldClub) {
    $comData['club'] = 'Silver Club';
    $class = 'silver-club';
} elseif ($comData['total_referral'] >= $goldClub) {
    $comData['club'] = 'Gold Club';
    $class = 'gold-club';
} else if ($comData['total_referral'] >= $bronzeClub && $comData['total_referral'] < $silverClub) {
    $comData['club'] = 'Bronze Club';
    $class = 'bronze-club';
} else {
    $comData['club'] = 'No Club';
    $class = 'no-club';
}
$user_club=$usersModel->saveUserClub($class,$this->secure->get_user_session()->id);
?>
<div class="inner-wrap reward-box">
    <div class="row">
        <div class="col-sm-2" title="Club">
            <!-- small box -->
            <div class="small-box <?php echo $class; ?>">
                <div class="inner">                    
                    <i class="fa fa-trophy fa-5x "></i>
                    <span><?php echo $comData['club']; ?></span>                                        
                </div>
            </div>
        </div>

        <div class="col-sm-2">
            <!-- small box -->
            <div class="small-box <?php echo $class; ?>" title="Referral">
                <div class="inner">
                    <i class="fa fa-user-plus fa-5x <?php echo $class; ?>"></i>
                    <span><?php echo ($comData['total_referral']) ? $comData['total_referral'] : 0; ?> Referrals</span>

                </div>
            </div>
        </div>

        <div class="col-sm-2">
            <!-- small box -->
            <div name="points" id="points" class="customButton" onClick="javascript:document.location.href = '<?php echo site_url('users/account/rewards'); ?>';">
                <div class="small-box <?php echo $class; ?>" title="Points">
                    <div class="inner">
                        <i class="fa fa-star fa-5x <?php echo $class; ?>"></i>
                        <span><?php echo ($comData['points']) ? $comData['points'] : 0; ?> Points</span>

                    </div>
                </div>

            </div>
        </div>

        <div class="col-sm-2">
            <!-- small box -->
            <div class="small-box <?php echo $class; ?>" title="Amount">
                <div class="inner">
                    <i class="fa fa-usd fa-5x <?php echo $class; ?>"></i>
                    <span><?php echo ($comData['amount']) ? $comData['amount'] : 0; ?></span>
                </div>
            </div>
        </div>
        <div class="col-sm-2">
            <!-- small box -->
            <div class="small-box <?php echo $class; ?>" title="Amount">
                <div class="inner">
                    <i class="fa fa-gift fa-5x <?php echo $class; ?>"></i>
                    <span><?php echo (isset($redeemPoint[0]) && $redeemPoint[0])?$redeemPoint[0]:'';?> Redeemed points ($<?php echo (isset($redeemPoint[1]) && $redeemPoint[1])?$redeemPoint[1]:'';?>)</span>
                </div>
            </div>
        </div>
        <div class="col-sm-2">
        <div class="rdm-link">
            <div class="small-box <?php echo $class; ?>" title="Redeem points">
                <div class="inner">
                    <a href="<?php echo site_url('/redemption/redeem/showlist'); ?>">
                        Redeem <br/>Points
<!--                        <img  src="{{ theme_url }}/assets/images/redeem-img.png" alt="redeem img"/>-->
                    </a>
                </div>
            </div>
        </div>
        </div>
    </div>

</div>
<?php if(isset($comData['amount']) && $comData['amount']>$this->settings->min_amount_redeem) { ?>
<?php if($this->settings->flash_message):?>
<div class="callout callout-info">
<p><?php echo $this->settings->flash_message;?></p>
</div>
<?php endif;?>
<?php } ?>