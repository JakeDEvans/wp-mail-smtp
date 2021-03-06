<?php

namespace WPMailSMTP\Providers\Mailgun;

use WPMailSMTP\Providers\OptionAbstract;

/**
 * Class Option.
 *
 * @since 1.0.0
 */
class Options extends OptionAbstract {

	/**
	 * Mailgun constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

		parent::__construct(
			array(
				'logo_url' => wp_mail_smtp()->plugin_url . '/assets/images/mailgun.png',
				'slug'     => 'mailgun',
				'title'    => esc_html__( 'Mailgun', 'wp-mail-smtp' ),
			)
		);
	}

	/**
	 * @inheritdoc
	 */
	public function display_options() {
		?>

		<table class="form-table">

			<!-- API Key -->
			<tr>
				<th scope="row">
					<label for="wp-mail-smtp-setting-<?php echo esc_attr( $this->get_slug() ); ?>-api-key"><?php esc_html_e( 'Private API Key', 'wp-mail-smtp' ); ?></label>
				</th>
				<td>
					<input name="wp-mail-smtp[<?php echo esc_attr( $this->get_slug() ); ?>][api_key]" type="text"
						value="<?php echo esc_attr( $this->options->get( $this->get_slug(), 'api_key' ) ); ?>"
						<?php echo $this->options->is_const_defined( $this->get_slug(), 'api_key' ) ? 'disabled' : ''; ?>
						id="wp-mail-smtp-setting-<?php echo esc_attr( $this->get_slug() ); ?>-api-key" class="regular-text" spellcheck="false"
					/>

					<p class="description">
						<?php
						printf(
							/* translators: %s - API key link. */
							esc_html__( 'Follow this link to get an API Key from Mailgun: %s.', 'wp-mail-smtp' ),
							'<a href="https://app.mailgun.com/app/account/security" target="_blank">' .
							esc_html__( 'Get a Private API Key', 'wp-mail-smtp' ) .
							'</a>'
						);
						?>
					</p>
				</td>
			</tr>

			<!-- Domain -->
			<tr>
				<th scope="row">
					<label for="wp-mail-smtp-setting-<?php echo esc_attr( $this->get_slug() ); ?>-domain"><?php esc_html_e( 'Domain Name', 'wp-mail-smtp' ); ?></label>
				</th>
				<td>
					<input name="wp-mail-smtp[<?php echo esc_attr( $this->get_slug() ); ?>][domain]" type="text"
						value="<?php echo esc_attr( $this->options->get( $this->get_slug(), 'domain' ) ); ?>"
						<?php echo $this->options->is_const_defined( $this->get_slug(), 'domain' ) ? 'disabled' : ''; ?>
						id="wp-mail-smtp-setting-<?php echo esc_attr( $this->get_slug() ); ?>-domain" class="regular-text" spellcheck="false"
					/>

					<p class="description">
						<?php
						printf(
							/* translators: %s - Domain Name link. */
							esc_html__( 'Follow this link to get a Domain Name from Mailgun: %s.', 'wp-mail-smtp' ),
							'<a href="https://app.mailgun.com/app/domains" target="_blank">' .
							esc_html__( 'Get a Domain Name', 'wp-mail-smtp' ) .
							'</a>'
						);
						?>
					</p>
				</td>
			</tr>

		</table>

		<?php
	}
}
