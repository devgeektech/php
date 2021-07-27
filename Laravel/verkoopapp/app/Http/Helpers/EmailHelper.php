<?php

namespace App\Helpers;

use Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;

class EmailHelper
{
    public $contents = null;

    //Function used for replacing hooks in our templates
    public function newTemplateMsg($template, $additionalHooks)
    {
        $mail_templates_dir = config('app.mail_dir');
        if (!file_exists($mail_templates_dir.$template)) { //File not found
            return false;
        }
        $this->contents = file_get_contents($mail_templates_dir.$template);
        //Check to see we can access the file / it has some contents
        if (!$this->contents || empty($this->contents)) {
            return false;
        } else {
            //Replace defined / custom hooks
            $this->contents = str_replace($additionalHooks['searchStrs'], $additionalHooks['subjectStrs'], $this->contents);

            return true;
        }
    }

    public function sendMail($to, $subject, $link_text = '', $title = null, $content = null, $view_name = '', $from = null, $text_on_link = null, $img_link = null, $table_data = null, $send_csv = null, $sender_name = null, $attachment_name = null, $bcc = null)
    {
        $link = '';
        if ($link_text != '') {
            $link = $link_text;
        }
        $text_on_link = $text_on_link == null ? 'Click Here' : $text_on_link;
        $img_link = $img_link == null ? '' : $img_link;
        $footer = '';
        $table_data = isset($table_data) ? $table_data : '';
        if ($content == null && $title == null) {
            $return = array();
            //Remove all new lines from the string
            $this->contents = trim(preg_replace('/\s\s+/', ' ', $this->contents));
            //Search for title,content in string
            preg_match_all('/@([^\:]+)\:([^;]+);/', $this->contents, $matches);
            $count = count($matches);
            for ($i = 0; $i < $count; ++$i) {
                if (isset($matches[1][$i]) && $matches[2][$i]) {
                    $return[$matches[1][$i]] = $matches[2][$i];
                }
            }
            $title = isset($return['title']) ? $return['title'] : 'Hello';
            $content = isset($return['content']) ? $return['content'] : 'Thank you for registering on FindDev';
            $footer = isset($return['footer']) ? $return['footer'] : '';
        }

        $data['bcc'] = null;
        if ($bcc != null && is_array($bcc)) {
            $data['bcc'] = $bcc;
        } elseif ($bcc != null) {
            $data['bcc'] = Config::get('mail.bcc');
        }

        if ($from == null) {
            $data['from'] = config('mail.from')['address'];
        } else {
            $data['from'] = $from;
        }
        $data['to'] = $to;

        $data['subject'] = $subject;
        $data['content'] = $content;
        $data['sender_name'] = $sender_name;
        $beautymail = app()->make(\Snowfire\Beautymail\Beautymail::class);
        if ($view_name != '') {
            try {
                $beautymail->send($view_name, ['title' => $title, 'content' => $content, 'footer' => $footer, 'link_activation' => $link, 'text_on_link' => $text_on_link, 'img_link' => $img_link, 'table_data' => $table_data], function ($message) use ($data) {
                    if ($data['sender_name'] != null) {
                        $message->to($data['to'])->from($data['from'], $data['sender_name'])->subject($data['subject']);
                    } else {
                        $message->to($data['to'])->from($data['from'])->subject($data['subject']);
                    }
                    if ($data['bcc'] != null && is_array($data['bcc'])) {
                        foreach ($data['bcc'] as $bcc_email) {
                            $message->bcc($bcc_email);
                        }
                    } elseif ($data['bcc'] != null) {
                        $message->bcc($data['bcc']);
                    }
                });
            } catch (\Swift_TransportException $e) {
                $response = $e->getMessage();
                Log::error('Error while sending email to '.$to.'. Subject - '.$subject.' Details'.$response."\n  =================================================================== \n");

                return false;
            }
        } else {
            try {
                $beautymail->send('emails.send', ['title' => $title, 'content' => $content, 'footer' => $footer, 'link_activation' => $link, 'text_on_link' => $text_on_link, 'img_link' => $img_link, 'table_data' => $table_data], function ($message) use ($data,$send_csv,$attachment_name) {
                    if ($send_csv != null) {
                        if ($attachment_name != null) {
                            $message->attachData($send_csv, $attachment_name);
                        } else {
                            $message->attachData($send_csv, 'invoice.xlsx');
                        }
                    }
                    if ($data['sender_name'] != null) {
                        $message->to($data['to'])->from($data['from'], $data['sender_name'])->subject($data['subject']);
                    } else {
                        $message->to($data['to'])->from($data['from'])->subject($data['subject']);
                    }
                    if ($data['bcc'] != null && is_array($data['bcc'])) {
                        foreach ($data['bcc'] as $bcc_email) {
                            $message->bcc($bcc_email);
                        }
                    } elseif ($data['bcc'] != null) {
                        $message->bcc($data['bcc']);
                    }
                });
            } catch (\Swift_TransportException $e) {
                $response = $e->getMessage();
                Log::error('Error while sending email to '.$to.'. Subject - '.$subject.' Details'.$response."\n  =================================================================== \n");

                return false;
            }
        }

        return true;
    }
}
