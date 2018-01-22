<?php
/**
 * The Class is used to allow developers to create
 * custom alerts
 */
class WSAL_Sensors_PaidMembershipProHooks extends WSAL_AbstractSensor
{
    public function HookEvents()
    {
        add_action('pmpro_added_order', array($this,   'EventPMProOrderAdded'), 10, 1);   // $order
        add_action('pmpro_delete_order', array($this,   'EventPMProOrderDelete'), 10, 2);  // $order->id, $order
        add_action('pmpro_update_order', array($this,   'EventPMProOrderUpdating'), 10, 1); // $order
        add_action('pmpro_updated_order', array($this,   'EventPMProOrderUpdated'), 10, 1); // $order

        add_action('pmpro_before_change_membership_level', array($this,   'EventPMProBeforeChangeMbrLevel'), 10, 4); //  $level_id, $user_id, $old_levels, $cancel_level
        add_action('pmpro_after_change_membership_level', array($this,   'EventPMProAfterChangeMbrLevel'), 10, 3); // $level_id, $user_id, $cancel_level
        add_action('pmpro_after_checkout', array($this,   'EventPMProAfterCheckout'), 10, 2);  // $user_id, $morder

        add_action('pmpro_delete_membership_level', array($this,   'EventPMProMbrLevelDelete'), 10, 1);  // $level_id
        add_action('pmpro_save_membership_level', array($this,   'EventPMProMbrLevelSave'), 10, 1);   // $level_id

        add_action('pmpro_delete_discount_code', array($this,   'EventPMProDiscountCodeDelete'), 10, 1); // $code_id
        add_action('pmpro_save_discount_code', array($this,   'EventPMProDiscountCodeSave'), 10, 1);  // $code_id
        add_action('pmpro_save_discount_code_level', array($this,   'EventPMProDiscountCodeLevelSave'), 10, 2);  // $edit, $level_id


        add_action('pmpro_subscription_cancelled', array($this,   'EventPMProSubscriptionCancelled'), 10, 1);  // $old_order
        add_action('pmpro_subscription_expired', array($this,   'EventPMProSubscriptionExpired'), 10, 1);  // $old_order
        add_action('pmpro_subscription_ipn_event_processed', array($this,   'EventPMProSubscriptionIPNProcessed'), 10, 2); // $ipn_id, $morder
        add_action('pmpro_subscription_payment_completed', array($this,   'EventPMProSubscriptionPmtCompleted'), 10, 1);  // $order
        add_action('pmpro_subscription_payment_failed', array($this,   'EventPMProSubscriptionPmtFailed'), 10, 1);  // $old_order
        add_action('pmpro_subscription_payment_went_past_due', array($this,   'EventPMProSubscriptionPmtPastDue'), 10, 1);  // $old_order
    }
    protected $old_meta = array();
    protected $updatingOrder = null;

    public function EventPMProOrderAdded ($order) {
        //  'PMPro Order (%ORDERID%) added for %User% (%USERID%) Level %MEMBERLEVEL% Amount %AMT% Status %STATUS% Type %TYPE% '
        if ($order->user_id == 0) {
            $name = $order->FirstName . ' ' . $order->LastName;
        } else {
            $user = get_user_by('id',$order->user_id);
            $name = $user->display_name;
        }

        $this->plugin->alerts->Trigger(8601, array(
            'ORDERID' => $order->id,
            'User' => $name,
            'USERID' => $order->user_id,
            'MEMBERLEVEL' => $order->membership_id,
            'AMT' => $order->total,
            'STATUS' => $order->status,
            'TYPE' => $order->payment_type,

        ));
    } /**  */
    public function EventPMProOrderDelete ($order_id, $order) {
        //  'PMPro Order (%ORDERID%) deleted for %User% (%USERID%) Level %MEMBERLEVEL% Amount %AMT% Status %STATUS% Type %TYPE% '
        if ($order->user_id == 0) {
            $name = $order->FirstName . ' ' . $order->LastName;
        } else {
            $user = get_user_by('id',$order->user_id);
            $name = $user->display_name;
        }

        $this->plugin->alerts->Trigger(8602, array(
            'ORDERID' => $order->ID,
            'User' => $name,
            'USERID' => $order->user_id,
            'MEMBERLEVEL' => $order->membership_id,
            'AMT' => $order->total,
            'STATUS' => $order->status,
            'TYPE' => $order->payment_type,

        ));
    } /**  */
    public function EventPMProOrderUpdating ($order) {

        global $updatingOrder;

        $old_order = new MemberOrder();
        $old_order->getMemberOrderByID($order->id);
        if (empty($old_order->datetime)) {
        	$old_order->datetime = $order->datetime;
        }
        $updatingOrder = $old_order;

    }
    public function EventPMProOrderUpdated ($order) {
        global $updatingOrder;

        $user = get_user_by('id',$order->user_id);

        $changes = array();
        if ($updatingOrder->code            != $order->code)                $changes['code'] =              array('old' => $updatingOrder->code,                'new' => $order->code);
        if ($updatingOrder->session_id      != $order->session_id)          $changes['session_id'] =        array('old' => $updatingOrder->session_id,          'new' => $order->session_id);
        if ($updatingOrder->user_id         != $order->user_id)             $changes['user_id'] =           array('old' => $updatingOrder->user_id,             'new' => $order->user_id);
        if ($updatingOrder->membership_id   != $order->membership_id)       $changes['membership_id'] =     array('old' => $updatingOrder->membership_id,       'new' => $order->membership_id);
        if ($updatingOrder->paypal_token    != $order->paypal_token)        $changes['paypal_token'] =      array('old' => $updatingOrder->paypal_token,        'new' => $order->paypal_token);
        if ($updatingOrder->billing->name   != $order->billing->name)       $changes['billingname'] =       array('old' => $updatingOrder->billing->name,       'new' => $order->billing->name);
        if ($updatingOrder->billing->street != $order->billing->street)     $changes['billingstreet'] =     array('old' => $updatingOrder->billing->street,     'new' => $order->billing->street);
        if ($updatingOrder->billing->city   != $order->billing->city)       $changes['billingcity'] =       array('old' => $updatingOrder->billing->city,       'new' => $order->billing->city);
        if ($updatingOrder->billing->state  != $order->billing->state)      $changes['billingstate'] =      array('old' => $updatingOrder->billing->state,      'new' => $order->billing->state);
        if ($updatingOrder->billing->zip    != $order->billing->zip)        $changes['billingzip'] =        array('old' => $updatingOrder->billing->zip,        'new' => $order->billing->zip);
        if ($updatingOrder->billing->country != $order->billing->country)   $changes['billingcountry'] =    array('old' => $updatingOrder->billing->country,    'new' => $order->billing->country);
        if ($updatingOrder->billing->phone  != $order->billing->phone)      $changes['billingphone'] =      array('old' => $updatingOrder->billing->phone,      'new' => $order->billing->phone);
        if ($updatingOrder->subtotal        != $order->subtotal)            $changes['subtotal'] =          array('old' => $updatingOrder->subtotal,            'new' => $order->subtotal);
        if ($updatingOrder->tax             != $order->tax)                 $changes['tax'] =               array('old' => $updatingOrder->tax,                 'new' => $order->tax);
        if ($updatingOrder->couponamount    != $order->couponamount)        $changes['couponamount'] =      array('old' => $updatingOrder->couponamount,        'new' => $order->couponamount);
        if ($updatingOrder->certificate_id  != $order->certificate_id)      $changes['certificate_id'] =    array('old' => $updatingOrder->certificate_id,      'new' => $order->certificate_id);
        if ($updatingOrder->certificateamount != $order->certificateamount) $changes['certificateamount'] = array('old' => $updatingOrder->certificateamount,   'new' => $order->certificateamount);
        if ($updatingOrder->total           != $order->total)               $changes['total'] =             array('old' => $updatingOrder->total,               'new' => $order->total);
        if ($updatingOrder->payment_type    != $order->payment_type)        $changes['payment_type'] =      array('old' => $updatingOrder->payment_type,        'new' => $order->payment_type);
        if ($updatingOrder->cardtype        != $order->cardtype)            $changes['cardtype'] =          array('old' => $updatingOrder->cardtype,            'new' => $order->cardtype);
        if ($updatingOrder->accountnumber   != $order->accountnumber)       $changes['accountnumber'] =     array('old' => $updatingOrder->accountnumber,       'new' => $order->accountnumber);
        if ($updatingOrder->expirationmonth != $order->expirationmonth)     $changes['expirationmonth'] =   array('old' => $updatingOrder->expirationmonth,     'new' => $order->expirationmonth);
        if ($updatingOrder->expirationyear  != $order->expirationyear)      $changes['expirationyear'] =    array('old' => $updatingOrder->expirationyear,      'new' => $order->expirationyear);
        if ($updatingOrder->status          != $order->status)              $changes['status'] =            array('old' => $updatingOrder->status,              'new' => $order->status);
        if ($updatingOrder->gateway         != $order->gateway)             $changes['gateway'] =           array('old' => $updatingOrder->gateway,             'new' => $order->gateway);
        if ($updatingOrder->gateway_environment != $order->gateway_environment)  $changes['gateway_environment'] =   array('old' => $updatingOrder->gateway_environment, 'new' => $order->gateway_environment);
        if ($updatingOrder->payment_transaction_id != $order->payment_transaction_id)  $changes['payment_transaction_id'] =  array('old' => $updatingOrder->payment_transaction_id, 'new' => $order->payment_transaction_id);
        if ($updatingOrder->subscription_transaction_id != $order->subscription_transaction_id)  $changes['subscription_transaction_id'] =  array('old' => $updatingOrder->subscription_transaction_id, 'new' => $order->subscription_transaction_id);
        if ($updatingOrder->datetime        != $order->datetime)            $changes['datetime'] =          array('old' => $updatingOrder->datetime,            'new' => $order->datetime);
        if ($updatingOrder->affiliate_id    != $order->affiliate_id)        $changes['affiliate_id'] =      array('old' => $updatingOrder->affiliate_id,        'new' => $order->affiliate_id);
        if ($updatingOrder->affiliate_subid != $order->affiliate_subid)     $changes['affiliate_subid'] =   array('old' => $updatingOrder->affiliate_subid,     'new' => $order->affiliate_subid);
        if ($updatingOrder->notes           != $order->notes)               $changes['notes'] =             array('old' => $updatingOrder->notes,               'new' => $order->notes);
        if ($updatingOrder->checkout_id     != $order->checkout_id)         $changes['checkout_id'] =       array('old' => $updatingOrder->checkout_id,         'new' => $order->checkout_id);

        foreach ($changes as $field => $values) {
            //  'PMPro Order (%ORDERID%) updated %FIELD% old value "%OLDVALUE%"  changed to "%NEWVALUE%" '

            $this->plugin->alerts->Trigger(8603, array(
                'ORDERID' => $order->id,
                'FIELD' => $field,
                'OLDVALUE' => $values{'old'},
                'NEWVALUE' => $values{'new'},

            ));
        }
    }
    private  $levelChanges = array();
    public function EventPMProBeforeChangeMbrLevel ($level_id, $user_id, $old_levels, $cancel_level = NULL){
        global $levelChanges;
        global $wpdb;

        $new_old_levels = array();
        foreach ($old_levels as $key => $old_level) {
            $mu = $wpdb->get_row("SELECT * FROM {$wpdb->pmpro_memberships_users} WHERE id = '$old_level->subscription_id'");
            $new_old_levels[$key] = array('mu' => $mu, 'old_level' => $old_level);
        }
        $levelChanges[$user_id] = ['level_id' => $level_id, 'old_levels' => $new_old_levels, 'cancel_level' => $cancel_level];
    }
    public function EventPMProAfterChangeMbrLevel ($level_id, $user_id, $cancel_level = NULL) {
        global $levelChanges;
        global $wpdb;
        $user = get_user_by('id',$user_id);

        if (!empty($levelChanges[$user_id]) ) {
            if (!empty($levelChanges[$user_id]['old_levels'])) {
                $old_levels = $levelChanges[$user_id]['old_levels'];
                 $old_mu = $old_levels[0]['mu'];
                 $old_level = $old_levels[0]['old_level'];

                if ($level_id > 0) {
                    $mu = $wpdb->get_row("SELECT *  FROM {$wpdb->pmpro_memberships_users} WHERE user_id = '$user_id' AND status = 'active'");
                    //  'PMPro Changing Level from %OLDLEVEL% to %NEWLEVEL% Start %STARTDATE% End %ENDDATE% CodeID %CODEID% for %USER%(%USERID%) '
                    $this->plugin->alerts->Trigger(8604, array(
                        'OLDLEVEL' => $old_level->id,
                        'NEWLEVEL' => $mu->membership_id,  //$level_id should be the same???
                        'STARTDATE' => $mu->startdate,
                        'ENDDATE' => $mu->enddate,
                        'CODEID' => $mu->code_id,
                        'USER' => $user->display_name,
                        'USERID' => $user_id,
                    ));
                } else {
                    //  'PMPro Canceling membership Level %OLDLEVEL%  for %USER%(%USERID%)  by user  %CurrUser% (%CurrUserID%)'
                    // should print below
                }
                foreach ($old_levels as $key => $values) {
                    $old_level = $values['old_level'];
                    $mu = $wpdb->get_row("SELECT *  FROM {$wpdb->pmpro_memberships_users} WHERE id = '$old_level->subscription_id'");
                    $old_mu = $values['mu'];
                    $diff = $this->diff_mu($mu,$old_mu );
                    if ($diff) {
                        // log new status & enddate
                        if ($level_id > 0) $msg = "Disable old Level ";
                        else $msg = "Cancel Level ";
                        //  'PMPro %MSG% %LEVEL% Status %OLDSTATUS% to %NEWSTATUS% Enddate %OLDENDDATE% to %NEWENDDATE% for %USER%(%USERID%)'

                        $this->plugin->alerts->Trigger(8618, array(
                            'MSG' => $msg,
                            'LEVEL' => $old_level->ID,
                            'OLDSTATUS' => $old_mu->status,  //$level should be the same???
                            'NEWSTATUS' => $mu->status,
                            'OLDENDDATE' => $old_mu->enddate,
                            'NEWENDDATE' => $mu->enddate,
                            'USER' => $user->display_name,
                            'USERID' => $user_id,
                        ));
                    }
                }
            }
        }


    }
    public function diff_mu($new_mu, $old_mu) {
        $diff = false;
        if ($new_mu->status != $old_mu->status ||
            $new_mu->enddate != $old_mu->enddate) {
            $diff = true;
        }
        return $diff;
    }

    public function EventPMProAfterCheckout ($user_id, $morder = null) {
        global $wpdb;
        $user = get_user_by('id',$user_id);

        if (is_null($morder)) {
            $msg = "No ";
            $order = 0;
            $code_id = 0;
        } else {
            $msg = "Got ";
            $row = $wpdb->get_row("SELECT * FROM $wpdb->pmpro_discount_codes_uses WHERE user_id = '$user_id' AND order_id = '$morder->id'");
            if ($row) {
            	$code_id = $row->code_id;
            } else {
	            $code_id = null;
            }
            $order = $morder->id;
        }
        //  'PMPro After Checkout %MSG% ORDER(%ORDERID%) Discount Code_ID(%CODEID%) Used for %User% (%USERID%) '
        $this->plugin->alerts->Trigger(8605, array(
            'MSG' => $msg,
            'ORDERID' => $order,
            'CODEID' => $code_id,
            'User' => $user->display_name,
            'USERID' => $user_id,
        ));
    }
    public function EventPMProDiscountCodeDelete ($code_id) {
        global $wpdb;
        $row = $wpdb->get_row("SELECT * FROM $wpdb->pmpro_discount_codes WHERE id = '$code_id' ");
        //  'PMPro Delete Discount code (%CODEID%)  Code %CODE% Starts %STARTS% Expires %EXPIRES% Uses %USES% '

        $this->plugin->alerts->Trigger(8606, array(
            'CODEID' => $row->id,
            'CODE' => $row->code,
            'STARTS' => $row->starts,
            'EXPIRES' => $row->expires,
            'USES' => $row->uses,
        ));
    } /**  */
    public function EventPMProDiscountCodeSave ($code_id) {
        global $wpdb;

        if ($code_id > 0) {
            $row = $wpdb->get_row("SELECT * FROM $wpdb->pmpro_discount_codes WHERE id = '$code_id' ");
            //  'PMPro %MSG% Disoount Code - ID(%CODEID%) Code %CODE% Starts %START% Expires %EXPIRES% uses %USES% '
            $this->plugin->alerts->Trigger(8607, array(
            	'MSG' => 'updated',
                'CODEID' => $row->id,
                'CODE' => $row->code,
                'START' => $row->starts,
                'EXPIRES' => $row->expires,
                'USES' => $row->uses,
            ));
        } else {
	        $code = preg_replace("/[^A-Za-z0-9\-]/", "", sanitize_text_field($_POST['code']));
	        $row = $wpdb->get_row("SELECT * FROM $wpdb->pmpro_discount_codes WHERE code = '$code' ");
	        $this->plugin->alerts->Trigger(8617, array(
	            'MSG' => 'added',
	            'CODEID' => $row->id,
	            'CODE' => $row->code,
	            'START' => $row->starts,
	            'EXPIRES' => $row->expires,
	            'USES' => $row->uses,            ));
        }
    }
    public function EventPMProDiscountCodeLevelSave ($edit, $level_id) {
        global $wpdb;
        $row = $wpdb->get_row("SELECT * FROM $wpdb->pmpro_discount_codes_levels WHERE code_id = '$edit' AND level_id = '$level_id' ");
        //  'PMPro Discount Code Level CodeID(%ORDERID%) LevelID %LEVELID% Pmt %PMT% Billing %BILL% Cycle# %CYCLENUM% Period %PERIOD% Limit %Limit% Amt %Amt% exp# %exp% ExpPeriod %ExpPeriod% '

        $this->plugin->alerts->Trigger(8608, array(
            'ORDERID' => $row->code_id,
            'LEVELID' => $row->level_id,
            'PMT' => $row->initial_payment,
            'BILL' => $row->billing_amount,
            'CYCLENUM' => $row->cycle_number,
            'PERIOD' => $row->cycle_period,
            'Limit' => $row->billing_limit,
            'Amt' => $row->trial_amount,
            'exp' => $row->expiration_number,
            'ExpPeriod' => $row->expiration_period
        ));
    }
    public function EventPMProMbrLevelSave ($level_id) {
        global $wpdb;
        $row = $wpdb->get_row("SELECT * FROM $wpdb->pmpro_membership_levels WHERE id = '$level_id' ");
        //  'PMPro Save Member Level - %name%(%id%) [%description%] Confirm %confirm% Pmt %pmt% Amt %amt% Cycle# %cyclenum% Period %period% Limit %limit% Trial %tamt% Trial Limit %tlimit% Exp# %expnum% Exp Period %expperiod% Signups %signups% '

        $this->plugin->alerts->Trigger(8609, array(
            'id'=>$row->id,
            'name' => $row->name,
            'description' => $row->description,
            'confirm' => $row->confirmation,
            'pmt' => $row->initial_payment,
            'amt' =>$row->billing_amount,
            'cyclenum' =>$row->cycle_number,
            'period' =>$row->cycle_period,
            'limit' => $row->billing_limit,
            'tamt' => $row->trial_amount,
            'tlimit' => $row->trial_limit,
            'expnum' => $row->expiration_number,
            'expperiod' => $row->expiration_period,
            'signups' => $row->allow_signups
        ));
    }
    public function EventPMProMbrLevelDelete ($level_id) {
        global $wpdb;
        $row = $wpdb->get_row("SELECT * FROM $wpdb->pmpro_membership_levels WHERE id = '$level_id' ");
        //  'PMPro Delete Member Level - %name%(%id%) [%description%] Confirm %confirm% Pmt %pmt% Amt %amt% Cycle# %cyclenum% Period %period% Limit %limit% Trial %tamt% Trial Limit %tlimit% Exp# %expnum% Exp Period %expperiod% Signups %signups% '

        $this->plugin->alerts->Trigger(8610, array(
            'id'=>$row->id,
            'name' => $row->name,
            'description' => $row->description,
            'confirm' => $row->confirmation,
            'pmt' => $row->initial_payment,
            'amt' =>$row->billing_amount,
            'cyclenum' =>$row->cycle_number,
            'period' =>$row->cycle_period,
            'limit' => $row->billing_limit,
            'tamt' => $row->trial_amount,
            'tlimit' => $row->trial_limit,
            'expnum' => $row->expiration_number,
            'expperiod' => $row->expiration_period,
            'signups' => $row->allow_signups
        ));
    } /**  */
    public function EventPMProSubscriptionCancelled ($old_order) {
        $user = get_user_by('id', $old_order->user_id);

        //  'PMPro Subscription Cancelled: Last Order (%ORDERID%) %CODE% for %User% (%USERID%) Gateway %GATEWAY%  Txn ID %subscription_transaction_id% Amount %AMT% Status %STATUS% Type %TYPE% '
        $this->plugin->alerts->Trigger(8611, array(
            'ORDERID' => $old_order->id,
            'CODE' => $old_order->code,
            'User' => $user->display_name,
            'USERID' => $user->ID,
            'GATEWAY' => $old_order->gateway,
            'subscription_transaction_id' => $old_order->subscription_transaction_id,
            'AMT' => $old_order->total,
            'STATUS' => $old_order->status,
            'TYPE' => $old_order->payment_type,
        ));
    }

    public function EventPMProSubscriptionExpired ($old_order) {
    	/** @var  $user */
        $user = get_user_by('id', $old_order->user_id);

        //  'PMPro Subscription Expired: Last Order (%ORDERID%) %CODE% for %User% (%USERID%) Gateway %GATEWAY%  Txn ID %subscription_transaction_id% Amount %AMT% Status %STATUS% Type %TYPE% '
        $this->plugin->alerts->Trigger(8612, array(
            'ORDERID' => $old_order->id,
            'CODE' => $old_order->code,
            'User' => $user->display_name,
            'USERID' => $user->ID,
            'GATEWAY' => $old_order->gateway,
            'subscription_transaction_id' => $old_order->subscription_transaction_id,
            'AMT' => $old_order->total,
            'STATUS' => $old_order->status,
            'TYPE' => $old_order->payment_type,
        ));
    }
    public function EventPMProSubscriptionIPNProcessed ($ipn_id, $morder) {
        $user = get_user_by('id', $morder->user_id);

        //  'PMPro Subscription IPN Processed: for %User% (%USERID%) Gateway %GATEWAY%  IPN ID %IPNID% Amount %AMT% Membership-ID %MEMBERSHIPID% Status %STATUS% Type %TYPE% '
        $this->plugin->alerts->Trigger(8613, array(
            'ORDERID' => $morder->id,
            'CODE' => $morder->code,
            'User' => $user->display_name,
            'USERID' => $user->ID,
            'GATEWAY' => $morder->gateway,
            'IPNID' => $morder->subscription_transaction_id,
            'AMT' => $morder->PaymentAmount,
            'MEMBERSHIPID' => $morder->membership_id,
            'STATUS' => $morder->status,
            'TYPE' => $morder->payment_type,
        ));
    }
    public function EventPMProSubscriptionPmtCompleted ($order) {
        $user = get_user_by('id', $order->user_id);

        //  'PMPro Subscription Pmt Completed:  Order (%ORDERID%) %CODE% for %User% (%USERID%) Gateway %GATEWAY%  Txn ID %subscription_transaction_id% Amount %AMT% Status %STATUS% Type %TYPE% '
        $this->plugin->alerts->Trigger(8614, array(
            'ORDERID' => $order->id,
            'CODE' => $order->code,
            'User' => $user->display_name,
            'USERID' => $user->ID,
            'GATEWAY' => $order->gateway,
            'subscription_transaction_id' => $order->subscription_transaction_id,
            'AMT' => $order->total,
            'STATUS' => $order->status,
            'TYPE' => $order->payment_type,
        ));
    }
    public function EventPMProSubscriptionPmtFailed ($old_order) {
        $user = get_user_by('id', $old_order->user_id);
        //  'PMPro Subscription Pmt Failed: Last Order (%ORDERID%) %CODE% for %User% (%USERID%) Gateway %GATEWAY%  Txn ID %subscription_transaction_id% Amount %AMT% Status %STATUS% Type %TYPE% '
        $this->plugin->alerts->Trigger(8615, array(
            'ORDERID' => $old_order->id,
            'CODE' => $old_order->code,
            'User' => $user->display_name,
            'USERID' => $user->ID,
            'GATEWAY' => $old_order->gateway,
            'subscription_transaction_id' => $old_order->subscription_transaction_id,
            'AMT' => $old_order->total,
            'STATUS' => $old_order->status,
            'TYPE' => $old_order->payment_type,
        ));
    }
    public function EventPMProSubscriptionPmtPastDue ($old_order) {
        $user = get_user_by('id', $old_order->user_id);
        //  'PMPro Subscription Pmt PastDue: Last Order (%ORDERID%) %CODE% for %User% (%USERID%) Gateway %GATEWAY%  Txn ID %subscription_transaction_id% Amount %AMT% Status %STATUS% Type %TYPE% '
        $this->plugin->alerts->Trigger(8616, array(
            'ORDERID' => $old_order->id,
            'CODE' => $old_order->code,
            'User' => $user->display_name,
            'USERID' => $user->ID,
            'GATEWAY' => $old_order->gateway,
            'subscription_transaction_id' => $old_order->subscription_transaction_id,
            'AMT' => $old_order->total,
            'STATUS' => $old_order->status,
            'TYPE' => $old_order->payment_type,
        ));
    }

}
