-- ----------------------------
--  Table structure for `config`
-- ----------------------------
DROP TABLE IF EXISTS `config`;
CREATE TABLE `config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `key` varchar(255) DEFAULT NULL,
  `value` varchar(255) DEFAULT NULL,
  `date_created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=latin1;

-- ----------------------------
--  Records of `config`
-- ----------------------------
BEGIN;
INSERT INTO `config` VALUES ('1', 'stripe_secret_key', '', '2014-12-03 22:13:59'), ('2', 'stripe_publishable_key', '', '2014-12-03 22:13:59'), ('3', 'paypal_environment', 'sandbox', '2014-12-11 12:24:45'), ('4', 'payment_type', 'input', '2014-12-03 22:13:59'), ('5', 'https_redirect', '0', '2014-12-03 22:13:59'), ('6', 'email', '', '2014-12-03 22:13:59'), ('7', 'show_description', '1', '2014-12-03 22:13:59'), ('8', 'page_title', 'Stripe Advanced Payment Terminal', '2014-12-03 22:13:59'), ('9', 'show_billing_address', '1', '2014-12-03 22:13:59'), ('10', 'name', '', '2014-12-04 09:49:55'), ('11', 'enable_paypal', '1', '2014-12-04 12:22:47'), ('12', 'enable_subscriptions', 'stripe_and_paypal', '2014-12-04 14:03:15'), ('13', 'paypal_email', '', '2014-12-04 15:59:49'), ('14', 'subscription_length', '0', '2014-12-08 14:11:49'), ('15', 'subscription_interval', '1', '2014-12-08 14:13:06'), ('16', 'currency', 'USD', '2014-12-29 21:29:16'), ('17', 'enable_trial', '0', '2014-12-31 10:48:23'), ('18', 'trial_days', '7', '2014-12-31 11:03:34'), ('19', 'notification_status', 'check', '2014-12-31 10:48:23');
COMMIT;

