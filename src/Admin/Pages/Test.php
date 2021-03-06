<?php

namespace WPMailSMTP\Admin\Pages;

use WPMailSMTP\MailCatcher;
use WPMailSMTP\WP;
use WPMailSMTP\Admin\PageAbstract;

/**
 * Class Test is part of Area, displays email testing page of the plugin.
 *
 * @since 1.0.0
 */
class Test extends PageAbstract {

	/**
	 * @var string Slug of a tab.
	 */
	protected $slug = 'test';

	/**
	 * @inheritdoc
	 */
	public function get_label() {
		return esc_html__( 'Email Test', 'wp-mail-smtp' );
	}

	/**
	 * @inheritdoc
	 */
	public function get_title() {
		return esc_html__( 'Send a Test Email', 'wp-mail-smtp' );
	}

	/**
	 * @inheritdoc
	 */
	public function display() {
		?>

		<form method="POST" action="">
			<?php $this->wp_nonce_field(); ?>

			<table class="form-table">
				<tr>
					<th scope="row">
						<label for="wp-mail-smtp-test-email"><?php esc_html_e( 'Send To', 'wp-mail-smtp' ); ?></label>
					</th>
					<td>
						<input name="wp-mail-smtp[test_email]" type="email" id="wp-mail-smtp-test-email" required class="regular-text" spellcheck="false" />
						<p class="description"><?php esc_html_e( 'Type an email address here and then click a button below to generate a test email.', 'wp-mail-smtp' ); ?></p>
					</td>
				</tr>
			</table>

			<p class="wp-mail-smtp-submit">
				<button type="submit" class="button-primary"><?php esc_html_e( 'Send Email', 'wp-mail-smtp' ); ?></button>
			</p>
		</form>

		<?php
	}

	/**
	 * @inheritdoc
	 */
	public function process_post( $data ) {

		$this->check_admin_referer();

		if ( isset( $data['test_email'] ) ) {
			$data['test_email'] = filter_var( $data['test_email'], FILTER_VALIDATE_EMAIL );
		}

		if ( empty( $data['test_email'] ) ) {
			WP::add_admin_notice(
				esc_html__( 'Test failed. Please use a valid email address and try to resend the test email.', 'wp-mail-smtp' ),
				WP::ADMIN_NOTICE_WARNING
			);
			return;
		}

		global $phpmailer;

		// Make sure the PHPMailer class has been instantiated.
		if ( ! is_object( $phpmailer ) || ! is_a( $phpmailer, 'PHPMailer' ) ) {
			require_once ABSPATH . WPINC . '/class-phpmailer.php';
			$phpmailer = new MailCatcher( true );
		}

		// Set SMTPDebug level, default is 3 (commands + data + connection status).
		$phpmailer->SMTPDebug = apply_filters( 'wp_mail_smtp_admin_test_email_smtp_debug', 3 );

		// Start output buffering to grab smtp debugging output.
		ob_start();

		// Send the test mail.
		$result = wp_mail(
			$data['test_email'],
			/* translators: %s - email address a test email will be sent to. */
			'WP Mail SMTP: ' . sprintf( esc_html__( 'Test mail to %s', 'wp-mail-smtp' ), $data['test_email'] ),
			esc_html__( 'This is a test email generated by the WP Mail SMTP WordPress plugin.', 'wp-mail-smtp' )
		);

		// Grab the smtp debugging output.
		$smtp_debug = ob_get_clean();

		/*
		 * Do the actual sending.
		 */
		if ( $result ) {
			WP::add_admin_notice(
				esc_html__( 'Your email was sent successfully!', 'wp-mail-smtp' ),
				WP::ADMIN_NOTICE_SUCCESS
			);
		} else {
			WP::add_admin_notice(
				'<p><strong>' . esc_html__( 'There was a problem while sending a test email.', 'wp-mail-smtp' ) . '</strong></p>' .
				'<p>' . esc_html__( 'The full debugging output is shown below:', 'wp-mail-smtp' ) . '</p>' .
				'<p><pre>' . print_r( $phpmailer, true ) . '</pre></p>' .
				'<p>' . esc_html__( 'The SMTP debugging output is shown below:', 'wp-mail-smtp' ) . '</p>' .
				'<p><pre>' . $smtp_debug . '</pre></p>',
				WP::ADMIN_NOTICE_ERROR
			);
		}
	}
}
