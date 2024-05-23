<?php

    namespace Ataccama;

    use Mandrill;
    use Mandrill_Error;


    class MandrillMailer
    {

        private $api_key;
        private $subaccount;

        private $templateParams = [];
        private $templateVars = [];

        /**
         * MandrillSender constructor.
         * @param string $apiKey
         * @param string $subaccount
         */
        function __construct(string $apiKey, string $subaccount)
        {
            $this->api_key = $apiKey;
            $this->subaccount = $subaccount;
        }

        /**
         * @return Mandrill
         * @throws Mandrill_Error
         */
        private function init()
        {
            return new Mandrill($this->api_key);
        }

        /**
         * Change default subaccount set from config.
         * @param string $subaccount
         * @return $this
         */
        public function setSubAccount(string $subaccount)
        {
            $this->subaccount = $subaccount;

            return $this;
        }

        /**
         * Change default api key from config.
         * @param string $apiKey
         * @return $this
         */
        public function setApiKey(string $apiKey)
        {
            $this->api_key = $apiKey;

            return $this;
        }

        /**
         * Template name corresponding in Mandrill administration.
         * @param string $name
         * @return MandrillMailer
         */
        public function templateName(string $name)
        {
            $this->templateParams['template_name'] = $name;

            return $this;
        }

        /**
         * Adds email sender.
         * @param string $email
         * @param string $name
         * @return MandrillMailer
         */
        public function addFrom(string $email, string $name = null)
        {
            $this->templateParams['from']['email'] = $email;
            if (isset($name)) {
                $this->templateParams['from']['name'] = $name;
            }

            return $this;
        }

        /**
         * Adds email recipient.
         * @param string $email
         * @param string $name
         * @return MandrillMailer
         */
        public function addTo(string $email, string $name = null)
        {
            $this->templateParams['to']['email'] = $email;
            if (isset($name)) {
                $this->templateParams['to']['name'] = $name;
            }

            return $this;
        }

        /**
         * Sets the subject of the message.
         * @param string $subject
         * @return MandrillMailer
         */
        public function setSubject(string $subject)
        {
            $this->templateParams['subject'] = $subject;

            return $this;
        }

        /**
         * Add content variables.
         * @param array $attrs
         * @return array
         */
        public function addAttributes(array $attrs)
        {
            $this->templateVars = $attrs;

            return $this->templateVars;
        }

        /**
         * Sets html body for email.
         * @param string $body
         * @return $this
         */
        public function setHtmlBody(string $body)
        {
            $this->templateParams['htmlBody'] = $body;

            return $this;
        }

        /**
         * @param      $name
         * @param      $content
         * @param null $type
         * @return $this
         */
        public function addAttachment($name, $content, $type = null)
        {
            $attachment = [
                "name"    => $name,
                "content" => base64_encode($content)
            ];

            if (isset($type)) {
                $attachment["type"] = $type;
            }

            $this->templateParams['attachments'][] = $attachment;

            return $this;
        }

        /**
         * Get all variables to the message.
         * @return array
         */
        private function setMessage()
        {
            $message = [
                'subject'        => $this->templateParams['subject'],
                'from_email'     => $this->templateParams['from']['email'],
                'to'             => [
                    [
                        'email' => $this->templateParams['to']['email'],
                        'type'  => 'to'
                    ]
                ],
                'track_opens'    => true,
                'track_clicks'   => true,
                'merge'          => true,
                'merge_language' => 'handlebars',
                'subaccount'     => $this->subaccount
            ];

            if(!empty($this->templateParams['from']['name'])) {
                $message['from_name'] = $this->templateParams['from']['name'];
            }

            if(!empty($this->templateParams['to']['name'])){
                $message['to'][]['name'] = $this->templateParams['to']['name'];
            }

            if (!empty($this->templateVars)) {
                foreach ($this->templateVars as $varKey => $varValue) {
                    $message['global_merge_vars'][] = [
                        'name'    => $varKey,
                        'content' => $varValue
                    ];
                }
            }

            if (!empty($this->templateParams['htmlBody'])) {
                $message['html'] = $this->templateParams['htmlBody'];
            }

            if (!empty($this->templateParams['attachments'])) {
                $message['attachments'] = $this->templateParams['attachments'];
            }

            return $message;
        }

        /**
         * @throws Mandrill_Error
         */
        public function send()
        {
            $email = $this->init();

            if (!empty($this->templateParams['template_name'])) {
                $result = $email->messages->sendTemplate($this->templateParams['template_name'], [],
                    $this->setMessage());

                return $result;
            } else {
                $result = $email->messages->send($this->setMessage());

                return $result;
            }
        }
    }