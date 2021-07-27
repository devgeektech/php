<?php
class MigrateEmails
{
    // CI DB Details 
    private $ciHostname = 'localhost';
    private $ciUsername = '';
    private $ciPassword = '';
    private $ciDbname = '';
    private $ciDbPrefix = 'ww_';
    // OC DB Details 
    private $ocHostname = 'localhost';
    private $ocUsername = '';
    private $ocPassword = '';
    private $ocDbname = '';
    private $ocDbPrefix = 'oc_';

    public function __construct()
    {
        $this->ciConn = mysqli_connect($this->ciHostname, $this->ciUsername, $this->ciPassword, $this->ciDbname) or die('Failed to connect to CI database ' . mysqli_error($this->ciConn));
        $this->ocConn = mysqli_connect($this->ocHostname, $this->ocUsername, $this->ocPassword, $this->ocDbname) or die('Failed to connect to OC database ' . mysqli_error($this->ocConn));
    }

    protected function query($conn, $query)
    {
        return mysqli_query($conn, $query);
    }

    protected function affectedRows($conn)
    {
        return mysqli_affected_rows($conn);
    }

    protected function fetch($query_res)
    {
        return mysqli_fetch_array($query_res);
    }

    protected function getCustomerGroup($customer_data)
    {
        $customer_groups = [
            0 => 1, // 0 to default customer group
            1 => 2,
            2 => 30,
            3 => 6,
            6 => 4,
            7 => 24,
            9 => 22,
            10 => 31,
            11 => 29,
            12 => 5,
            13 => 27,
            14 => 7,
            15 => 3,
            16 => 8,
            17 => 21,
            19 => 19,
            20 => 25,
            21 => 26,
            24 => 12,
            25 => 1, // test vendor customer group to 1
            26 => 11,
            27 => 20,
            28 => 23,
            29 => 28,
            30 => 9,
            31 => 32,
            32 => 15,
            33 => 10,
            34 => 14,
            35 => 17,
            36 => 16,
            37 => 33,
            38 => 13,
            39 => 18
        ];

        return $customer_groups[$customer_data['vendor_id']] ?? 1;
    }

    public function getEmailTemplateKey($template_name)
    {
        $templates = [
            'payment_complete_confirmation_email' => 'order.payment_complete',
            'forgotten_password_reset_email' => 'customer.forgotten',
            'share_partner_invitation_before_register' => 'sharepartner.invitation_register',
            'registered_user_confirmation_email' => 'customer.register',
            'confirmation_for_credit_card_three_payment' => 'order.cc_3pay_confirmation',
            'payment_notification_before_auto_payment' => 'order.notify_before_auto_payment',
            'successful_auto_payment' => 'order.auto_pay_success',
            'share_partner_register' => 'customer.share_partner_register',
            'failed_auto_payment' => 'order.auto_payment_failed',
            'confirmation_for_check_three_payment' => 'order.check_3pay_confirmation',
            'share_partner_invitation' => 'customer.sharepartner_invitation',
            'partial_payment_confirmation' => 'order.partial_pay_confirmation',
            'invoice_payment_complete_confirmation_email' => 'order.inv_payment_confirmation',
            'payment_pending_confirmation_email' => 'order.payment_pending_confirm',
            'invoice_partial_payment_confirmation' => 'order.inv_partial_pay_confirm',
            'waiting_list_user_confirmation_email' => 'customer.waiting_list',
            'check_payment_notification' => 'order.check_pay_notification',
            'order_comments' => 'order.comment_posted',
            'confirmation_for_check_four_payment' => 'order.check_4pay_confirmation',
            'confirmation_for_credit_card_four_payment' => 'order.cc_4pay_confirmation',
            'pending_payment_reminder' => 'order.pending_payment_reminder',
        ];
        return (isset($templates[$template_name])) ? $templates[$template_name] : '';
    }

    public function getTemplateByKey($template_key)
    {
        $q = $this->query($this->ocConn, "SELECT * FROM {$this->ocDbPrefix}emailtemplate WHERE emailtemplate_key='{$template_key}'");
        if ($this->affectedRows($this->ocConn) > 0) {
            return $this->fetch($q);
        }
        return 0;
    }

    public function start()
    {
        $emails = $this->query($this->ciConn, "SELECT * FROM {$this->ciDbPrefix}email_logs el INNER JOIN {$this->ciDbPrefix}contacts c ON c.id = el.user_id WHERE date_format(el.sent_date, '%Y%') > 2018");
        if ($this->affectedRows($this->ciConn) > 0) {
            $count = 1;
            while ($row = $this->fetch($emails)) {
                $customer_id = $row['user_id'];
                if ($row['template_name'] == 'No Template') {
                    continue;
                }
                $template_key = $this->getEmailTemplateKey($row['template_name']);
                $template = $this->getTemplateByKey($template_key);

                $insert_data = [
                    'emailtemplate_key' => $template_key,
                    'emailtemplate_id' => $template['emailtemplate_id'] ?? 0,
                    'emailtemplate_config_id' => $template['emailtemplate_config_id'] ?? null,
                    'customer_id' => $customer_id,
                    'customer_group_id' => $this->getCustomerGroup($row),
                    'language_id' => 1,
                    'order_id' => $template['order_id'] ?? 0,
                    'store_id' => $template['store_id'] ?? 0,
                    'emailtemplate_log_added' => $row['sent_date'],
                    'emailtemplate_log_sent' => $row['sent_date'],
                    'emailtemplate_log_read' => $template['emailtemplate_log_read'] ?? '',
                    'emailtemplate_log_to' => $row['to_address'],
                    'emailtemplate_log_from' => 'info@stoneledge.farm',
                    'emailtemplate_log_reply_to' => $template['emailtemplate_log_reply_to'] ?? '',
                    'emailtemplate_log_cc' => $template['emailtemplate_log_cc'] ?? '',
                    'emailtemplate_log_sender' => 'Stoneledge Farm',
                    'emailtemplate_log_subject' => $row['subject'],
                    'emailtemplate_log_heading' => $template['emailtemplate_log_heading'] ?? '',
                    'emailtemplate_log_content' => $this->ciConn->real_escape_string($row['message']),
                    'emailtemplate_log_enc' => $template['emailtemplate_log_enc'] ?? '',
                    'emailtemplate_log_is_sent' => 1,
                ];

                $cols = '`' . implode('`, `', array_keys($insert_data)) . '`';
                $values = '"' . implode('", "', array_values($insert_data)) . '"';

                $sql = "INSERT INTO {$this->ocDbPrefix}emailtemplate_logs({$cols}) VALUES ({$values})";
                $this->query($this->ocConn, $sql);
                echo "{$count} email(s) inserted" . PHP_EOL;
                $count++;
            }
        }
    }
}

$emailMigrate = new MigrateEmails;
$emailMigrate->start();