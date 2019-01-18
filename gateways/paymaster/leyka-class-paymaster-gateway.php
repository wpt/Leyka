<?php if (!defined('WPINC')) die;

/**
 * Leyka_Robokassa_Gateway class
 */
class Leyka_Paymaster_Gateway extends Leyka_Gateway {

    protected static $_instance;

    protected function _set_attributes() {

        $this->_id = 'paymaster';
        $this->_title = __('Paymaster', 'leyka');

        $this->_description = apply_filters(
            'leyka_gateway_description',
            __('Paymaster system allows a simple and safe way to pay for goods and services with bank cards and other means through internet. You will have to fill a payment form, and then you will be redirected to the <a href="https://www.paymaster.ru/">Paymaster</a> secure payment page to enter your bank card data and to confirm your payment.', 'leyka'),
            $this->_id
        );

        $this->_docs_link = '';
        $this->_registration_link = '//info.paymaster.ru/check/';

        $this->_min_commission = 2.8;
        $this->_receiver_types = array('legal');

    }

    protected function _set_options_defaults() {

        if($this->_options) {
            return;
        }

        $this->_options = array(
            'paymaster_merchant_id' => array(
                'type' => 'text',
                'title' => __('Paymaster merchant ID', 'leyka'),
                'comment' => __('Please find your merchant id in PayMaster merchant Control Panel.', 'leyka'),
                'required' => true,
                'placeholder' => sprintf(__('E.g., %s', 'leyka'), 'ct5b8f62-297f-4d19-b805-249cab7a37ed'),
            ),
            'paymaster_secret_word' => array(
                'type' => 'text',
                'title' => __('Secret word', 'leyka'),
                'comment' => __('Paymaster secret word, please set it also in PayMaster merchant backoffice.', 'leyka'),
                'required' => true,
                'is_password' => true,
            ),
            'paymaster_hash_method' => array(
                'type' => 'select',
                'default' => 'md5',
                'title' => __('Hash security method', 'leyka'),
                'comment' => __('Please, find your hash method in PayMaster merchant Control Panel.', 'leyka'),
                'required' => true,
                'list_entries' => array('md5' => 'md5', 'sha1' => 'sha1', 'sha256' => 'sha256'),
            ),
        );

    }

    protected function _initialize_pm_list() {
        if(empty($this->_payment_methods['paymaster_all'])) {
            $this->_payment_methods['paymaster_all'] = Leyka_Paymaster_All::getInstance();
        }
    }

    public function process_form($gateway_id, $pm_id, $donation_id, $form_data) {
    }

    public function submission_redirect_url($current_url, $pm_id) {
        return 'https://paymaster.ru/Payment/Init';
    }

    public function submission_form_data($form_data_vars, $pm_id, $donation_id) {

        if( !array_key_exists($pm_id, $this->_payment_methods) ) {
            return $form_data_vars;
        }

        $donation = new Leyka_Donation($donation_id);
        $amount = number_format((float)$donation->amount, 2, '.', '');

        $pm_curr = $pm_id;
        switch ($pm_id) {
            case 'paymaster_all':
                $pm_curr = 'RUB';
                break;
            case 'Other':
                $pm_curr = 'RUB';
                break;
        }

        return array(
            'LMI_MERCHANT_ID' => leyka_options()->opt('paymaster_merchant_id'),
            'LMI_PAYMENT_AMOUNT' => $amount,
            'LMI_PAYMENT_NO' => $donation_id,
            'LMI_CURRENCY' => $pm_curr,
            'LMI_PAYMENT_DESC' => sprintf(__('PayMaster service donation payment #%s', 'leyka'), $donation_id),
            'SIGN' => md5(leyka_options()->opt('paymaster_merchant_id').':'.$amount.':'.$donation_id.':'.leyka_options()->opt('paymaster_secret_word')),
            'LMI_PAYMENT_NOTIFICATION_URL' => home_url('leyka/service/' . $this->_id . '/response/'),
            'LMI_SUCCESS_URL' => leyka_get_campaign_success_page_url($donation->campaign_id),
            'LMI_FAILURE_URL' => leyka_get_campaign_failure_page_url($donation->campaign_id),
        );

    }

    public function _handle_service_calls($call_type = '') {

        if (empty($_REQUEST['LMI_PAYMENT_NO'])) {

            $message = __('This message has been sent because a call to your Paymaster callback was made without LMI_PAYMENT_NO parameter given. The details of the call are below:', 'leyka') . "\n\r\n\r";

            $message .= "THEIR_POST:\n\r" . print_r($_POST, true) . "\n\r\n\r";
            $message .= "GET:\n\r" . print_r($_GET, true) . "\n\r\n\r";
            $message .= "SERVER:\n\r" . print_r($_SERVER, true) . "\n\r\n\r";

            wp_mail(get_option('admin_email'), __('Paymaster callback error - missing LMI_PAYMENT_NO value', 'leyka'), $message);
            status_header(200);
            die();

        }

        $donation = new Leyka_Donation((int)$_REQUEST['LMI_PAYMENT_NO']);

        // Sign and hash
        $sign = $this->_get_signature($_REQUEST);
        $hash = $this->_get_hash($_REQUEST);

        if(
            empty($_REQUEST['SIGN'])
            || empty($_REQUEST['LMI_HASH'])
            || $_REQUEST['SIGN'] !== $sign
            || $_REQUEST['LMI_HASH'] !== $hash
        ) {

            $message = __('This message has been sent because a call to your Paymaster callback was called with wrong digital signature. It may mean that someone is trying to hack your payment website. The details of the call are below:', 'leyka') . "\n\r\n\r";

            $message .= "POST:\n\r" . print_r($_POST, true) . "\n\r\n\r";
            $message .= "GET:\n\r" . print_r($_GET, true) . "\n\r\n\r";
            $message .= "SERVER:\n\r" . print_r($_SERVER, true) . "\n\r\n\r";
            $message .= "Signature from request:\n\r" . print_r($_REQUEST['SignatureValue'], true) . "\n\r\n\r";
            $message .= "Signature calculated:\n\r" . print_r($sign, true) . "\n\r\n\r";

            wp_mail(get_option('admin_email'), __('Paymaster digital signature check failed!', 'leyka'), $message);
            die();

        }

        if($donation->status !== 'funded') {

            $donation->add_gateway_response($_REQUEST);
            $donation->status = 'funded';

            $_REQUEST['IncCurrLabel'] = empty($_REQUEST['IncCurrLabel']) ? '' : substr_replace($_REQUEST['IncCurrLabel'], '', -1);

            if(
                $donation->pm_id != $_REQUEST['IncCurrLabel'] &&
                array_key_exists($_REQUEST['IncCurrLabel'], $this->_payment_methods)
            ) {
                $donation->pm_id = $_REQUEST['IncCurrLabel'];
            }

            Leyka_Donation_Management::send_all_emails($donation->id);

            die('OK'.$_REQUEST['InvId']);

        } else {
            die();
        }

    }

    protected function _get_hash($request) {

        foreach(array('LMI_MERCHANT_ID', 'LMI_PAYMENT_NO', 'LMI_SYS_PAYMENT_ID', 'LMI_SYS_PAYMENT_DATE', 'LMI_PAYMENT_AMOUNT', 'LMI_CURRENCY', 'LMI_PAID_AMOUNT', 'LMI_PAID_CURRENCY', 'LMI_PAYMENT_SYSTEM', 'LMI_SIM_MODE',) as $key) {
            $request[$key] = $request[$key] ? $request[$key] : '';
        }

        return base64_encode(hash(
            leyka_options()->opt('paymaster_hash_method'),
            $request['LMI_MERCHANT_ID'].';'.$request['LMI_PAYMENT_NO'].';'.$request['LMI_SYS_PAYMENT_ID'].';'.$request['LMI_SYS_PAYMENT_DATE'].';'.$request['LMI_PAYMENT_AMOUNT'].';'.$request['LMI_CURRENCY'].';'.$request['LMI_PAID_AMOUNT'].';'.$request['LMI_PAID_CURRENCY'].';'.$request['LMI_PAYMENT_SYSTEM'].';'.$request['LMI_SIM_MODE'].';'.leyka_options()->opt('paymaster_secret_word'),
            true
        ));

    }

    protected function _get_signature($request) {

        $request['LMI_PAYMENT_NO'] = $request['LMI_PAYMENT_NO'] ? $request['LMI_PAYMENT_NO'] : '';
        $request['LMI_PAYMENT_AMOUNT'] = $request['LMI_PAYMENT_AMOUNT'] ? $request['LMI_PAYMENT_AMOUNT'] : '';

        $sign_string = leyka_options()->opt('paymaster_merchant_id').':'.$request['LMI_PAYMENT_AMOUNT'].':' . $request['LMI_PAYMENT_NO'].':'.leyka_options()->opt('paymaster_secret_word');

        return md5($sign_string);

    }

    protected function _get_value_if_any($arr, $key, $val = false) {
        return empty($arr[$key]) ? '' : ($val ? $val : $arr[$key]);
    }

    public function get_gateway_response_formatted(Leyka_Donation $donation) {

        if( !$donation->gateway_response ) {
            return array();
        }

        $vars = maybe_unserialize($donation->gateway_response);
        if( !$vars || !is_array($vars) ) {
            return array();
        }

        return array(
            __('Outcoming sum:', 'leyka') => $this->_get_value_if_any($vars, 'OutSum', !empty($vars['OutSum']) ? round($vars['OutSum'], 2) : false),
            __('Incoming sum:', 'leyka') => $this->_get_value_if_any($vars, 'IncSum', !empty($vars['IncSum']) ? round($vars['IncSum'], 2) : false),
            __('Invoice ID:', 'leyka') => $this->_get_value_if_any($vars, 'InvId'),
            __('Signature value (sent from Paymaster):', 'leyka') => $this->_get_value_if_any($vars, 'SignatureValue'),
            __('Payment method:', 'leyka') => $this->_get_value_if_any($vars, 'PaymentMethod'),
            __('Paymaster currency label:', 'leyka') => $this->_get_value_if_any($vars, 'IncCurrLabel'),
        );

    }

}

class Leyka_Paymaster_All extends Leyka_Payment_Method {

    protected static $_instance = null;

    public function _set_attributes() {

        $this->_id = 'paymaster_all';
        $this->_gateway_id = 'paymaster';
        $this->_category = 'misc';

        $this->_description = apply_filters(
            'leyka_pm_description',
            __('Paymaster system allows a simple and safe way to pay for goods and services with bank cards and other means through internet. You will have to fill a payment form, and then you will be redirected to the <a href="https://www.paymaster.ru/">Paymaster</a> secure payment page to enter your bank card data and to confirm your payment.', 'leyka'),
            $this->_id,
            $this->_gateway_id,
            $this->_category
        );

        $this->_label_backend = __('Paymaster smart payment', 'leyka');
        $this->_label = __('Paymaster smart payment', 'leyka');

        $this->_icons = apply_filters('leyka_icons_'.$this->_gateway_id.'_'.$this->_id, array(
            LEYKA_PLUGIN_BASE_URL.'gateways/paymaster/icons/paymaster_all.svg',
            LEYKA_PLUGIN_BASE_URL.'img/pm-icons/card-visa.svg',
            LEYKA_PLUGIN_BASE_URL.'img/pm-icons/card-mastercard.svg',
            LEYKA_PLUGIN_BASE_URL.'img/pm-icons/card-maestro.svg',
            LEYKA_PLUGIN_BASE_URL.'img/pm-icons/card-mir.svg',
        ));

        $this->_supported_currencies[] = 'rur';
        $this->_default_currency = 'rur';

    }

}

/**
 * Paymaster method add
 */
function leyka_add_gateway_paymaster() { // Use named function to leave a possibility to remove/replace it on the hook
    leyka_add_gateway(Leyka_Paymaster_Gateway::getInstance());
}

add_action('leyka_init_actions', 'leyka_add_gateway_paymaster');