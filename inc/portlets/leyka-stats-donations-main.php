<?php if( !defined('WPINC') ) die;
/**
 * Leyka Portlet: Main donation types stats
 * Description: A portlet to display simple statistics for main donation types (single & recurring).
 *
 * Title: Main statistics
 * Thumbnail: /img/stats-donation-types.svg
 **/

$data = Leyka_Donations_Main_Stats_Portlet_Controller::get_instance()->get_template_data($params);?>

<div class="portlet-row">
    <div class="row-label"><?php _e('Donations amount', 'leyka');?></div>
    <div class="row-data">

        <?php if(empty($data['donations_amount'])) {?>
        <div class="no-data"><?php _e('No data available', 'leyka');?></div>
        <?php } else {?>

        <div class="main-number"><?php echo $data['donations_amount'].'&nbsp;'.leyka()->opt('currency_'.leyka()->opt('main_currency').'_label');?></div>
        <div class="percent <?php echo $data['donations_amount_delta_percent'] < 0 ? 'negative' : 'positive';?>"><?php echo $data['donations_amount_delta_percent'];?></div>

        <?php }?>

    </div>
</div>

<div class="portlet-row">
    <div class="row-label"><?php _e('Donors total', 'leyka');?></div>
    <div class="row-data">

        <?php if(empty($data['donors_number'])) {?>
            <div class="no-data"><?php _e('No data available', 'leyka');?></div>
        <?php } else {?>

            <div class="main-number"><?php echo $data['donors_number'];?></div>
            <div class="percent <?php echo $data['donors_number_delta_percent'] < 0 ? 'negative' : 'positive';?>"><?php echo $data['donors_number_delta_percent'];?></div>

        <?php }?>

    </div>
</div>

<div class="portlet-row">
    <div class="row-label"><?php _e('Donations average amount', 'leyka');?></div>
    <div class="row-data">

        <?php if(empty($data['donations_amount_avg'])) {?>
            <div class="no-data"><?php _e('No data available', 'leyka');?></div>
        <?php } else {?>

            <div class="main-number"><?php echo $data['donations_amount_avg'];?></div>
            <div class="percent <?php echo $data['donations_amount_avg_delta_percent'] < 0 ? 'negative' : 'positive';?>"><?php echo $data['donations_amount_avg_delta_percent'];?></div>

        <?php }?>

    </div>
</div>