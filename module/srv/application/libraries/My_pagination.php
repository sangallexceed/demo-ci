<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once BASEPATH . '/libraries/Pagination.php';
class My_Pagination extends CI_Pagination {

	/**
	 * Base URL
	 *
	 * The page that we're linking to
	 *
	 * @var	string
	 */
	protected $base_url		= '';

	/**
	 * Prefix
	 *
	 * @var	string
	 */
	protected $prefix = '';

	/**
	 * Suffix
	 *
	 * @var	string
	 */
	protected $suffix = '';

	/**
	 * Total number of items
	 *
	 * @var	int
	 */
	protected $total_rows = 0;

	/**
	 * Number of links to show
	 *
	 * Relates to "digit" type links shown before/after
	 * the currently viewed page.
	 *
	 * @var	int
	 */
	protected $num_links = 5;

	/**
	 * Items per page
	 *
	 * @var	int
	 */
	public $per_page = 3;

	/**
	 * Current page
	 *
	 * @var	int
	 */
	public $cur_page = 0;

	/**
	 * Use page numbers flag
	 *
	 * Whether to use actual page numbers instead of an offset
	 *
	 * @var	bool
	 */
	protected $use_page_numbers = FALSE;

	/**
	 * First link
	 *
	 * @var	string
	 */
	protected $first_link = '&lsaquo;&lsaquo;';

	/**
	 * Next link
	 *
	 * @var	string
	 */
	protected $next_link = '次へ';

	/**
	 * Previous link
	 *
	 * @var	string
	 */
	protected $prev_link = '前へ';

	/**
	 * Last link
	 *
	 * @var	string
	 */
	protected $last_link = '&rsaquo; &rsaquo;';

	/**
	 * URI Segment
	 *
	 * @var	int
	 */
	protected $uri_segment = 0;

	/**
	 * Full tag open
	 *
	 * @var	string
	 */
	protected $full_tag_open = '<ul class=\'pagination\'>';

	/**
	 * Full tag close
	 *
	 * @var	string
	 */
	protected $full_tag_close = '</ul>';

	/**
	 * First tag open
	 *
	 * @var	string
	 */
	protected $first_tag_open = '<li>';

	/**
	 * First tag close
	 *
	 * @var	string
	 */
	protected $first_tag_close = '</li>';

	/**
	 * Last tag open
	 *
	 * @var	string
	 */
	protected $last_tag_open = '<li>';

	/**
	 * Last tag close
	 *
	 * @var	string
	 */
	protected $last_tag_close = '</li>';

	/**
	 * First URL
	 *
	 * An alternative URL for the first page
	 *
	 * @var	string
	 */
	protected $first_url = '';

	/**
	 * Current tag open
	 *
	 * @var	string
	 */
	protected $cur_tag_open = '<li class=\'disabled\'><li class=\'active\'><a href=\'#\'>';

	/**
	 * Current tag close
	 *
	 * @var	string
	 */
	protected $cur_tag_close = '</a></li>';

	/**
	 * Next tag open
	 *
	 * @var	string
	 */
	protected $next_tag_open = '<li>';

	/**
	 * Next tag close
	 *
	 * @var	string
	 */
	protected $next_tag_close = '</li>';

	/**
	 * Previous tag open
	 *
	 * @var	string
	 */
	protected $prev_tag_open = '<li>';

	/**
	 * Previous tag close
	 *
	 * @var	string
	 */
	protected $prev_tag_close = '</li>';

	/**
	 * Number tag open
	 *
	 * @var	string
	 */
	protected $num_tag_open = '<li>';

	/**
	 * Number tag close
	 *
	 * @var	string
	 */
	protected $num_tag_close = '</li>';

	/**
	 * Page query string flag
	 *
	 * @var	bool
	 */
	protected $page_query_string = TRUE;

	/**
	 * Query string segment
	 *
	 * @var	string
	 */
	protected $query_string_segment = 'start';

	/**
	 * Display pages flag
	 *
	 * @var	bool
	 */
	protected $display_pages = TRUE;

	/**
	 * Attributes
	 *
	 * @var	string
	 */
	protected $_attributes = '';

	/**
	 * Link types
	 *
	 * "rel" attribute
	 *
	 * @see	CI_Pagination::_attr_rel()
	 * @var	array
	 */
	protected $_link_types = array();

	/**
	 * Reuse query string flag
	 *
	 * @var	bool
	 */
	protected $reuse_query_string = FALSE;

	/**
	 * Use global URL suffix flag
	 *
	 * @var	bool
	 */
	protected $use_global_url_suffix = FALSE;

	/**
	 * Data page attribute
	 *
	 * @var	string
	 */
	protected $data_page_attr = 'data-ci-pagination-page';
}
