<?php

class CVM_Jnews_Actions_Compatibility {
	/**
	 * Theme name
	 * @var string
	 */
	private $theme_name;

	/**
	 * CVM_Jnews_Actions_Compatibility constructor.
	 *
	 * @param string $theme_name
	 */
	public function __construct( $theme_name ) {
		$this->theme_name = $theme_name;
		add_filter( 'vimeotheque_pro\theme_support', array( $this, 'theme_support' ) );
		add_filter( 'vimeotheque\import_success', array( $this, 'add_post_meta' ), 10, 4 );
	}

	/**
	 * @param array $themes
	 *
	 * @return array
	 */
	public function theme_support( $themes ) {
		$theme_name = strtolower( $this->theme_name );
		$themes[ $theme_name ] = array(
			'post_type'    => 'post',
			'taxonomy'     => false,
			'tag_taxonomy' => 'post_tag',
			'post_meta'    => array(),
			'post_format'  => 'video',
			'theme_name'   => $this->theme_name,
			'url'          => 'https://themeforest.net/item/jnews-one-stop-solution-for-web-publishing/20566392?ref=cboiangiu',
		);

		return $themes;
	}

	/**
	 * @param $post_id
	 * @param $video
	 * @param $theme_import
	 * @param $post_type
	 *
	 * @return string
	 */
	public function add_post_meta( $post_id, $video, $theme_import, $post_type ) {
		if ( ! $theme_import ) {
			return;
		}
		
		update_post_meta(
			$post_id,
			'jnews_video_cache',
			[
				'title' => $video['title'],
				'thumbnails' => $video['thumbnails'][0],
				'duration' => $video['_duration'],
				'description' => $video['description']
			]
		);

		update_post_meta(
			$post_id,
			'jnews_single_post',
			[
				'video' => \Vimeotheque_Pro\Helper::get_video_url( $video['video_id'] )
			]
		);
	}
}