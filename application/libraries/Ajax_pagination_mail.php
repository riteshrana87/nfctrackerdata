<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 4.3.2 or newer
 *
 * @package		CodeIgniter
 * @author		ExpressionEngine Dev Team
 * @copyright	Copyright (c) 2006, EllisLab, Inc.
 * @license		http://codeigniter.com/user_guide/license.html
 * @link		http://codeigniter.com
 * @since		Version 1.0
 * @filesource
 */
// ------------------------------------------------------------------------

/**
 * Pagination Class
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	Pagination
 * @author		ExpressionEngine Dev Team
 * @link		http://codeigniter.com/user_guide/libraries/pagination.html
 *
 * @download from  http://www.joelsays.com/downloads/jquery-pagination.zip
 */
class Ajax_pagination_mail {

    var $base_url = ''; // The page we are linking to
    var $total_rows = ''; // Total number of items (database results)
    var $per_page = 10; // Max number of items you want shown per page
    var $num_links = 2; // Number of "digit" links to show before/after the currently viewed page
    var $cur_page = 0; // The current page being viewed
    var $first_link = '&lsaquo;';
    var $next_link = '&gt;';
    var $prev_link = '&lt;';
    var $last_link = '&rsaquo;';
    var $uri_segment = 4;
    var $full_tag_open = '<div class="row">';
    var $full_tag_close = '</div>';
    var $first_tag_open = '';
    var $first_tag_close = '';
    var $last_tag_open = '';
    var $last_tag_close = '';
    var $cur_tag_open = '&nbsp;<li class="paginate_button active"><a class="ajax_paging" tabindex="0" data-dt-idx="1" aria-controls="example1" href="#"><b>';
    var $cur_tag_close = '</b></a></li>';
    var $next_tag_open = '';
    var $next_tag_close = '';
    var $prev_tag_open = '';
    var $prev_tag_close = '';
    var $num_tag_open = '';
    var $num_tag_close = '';
    // Added By Tohin
    var $js_rebind = '';
    var $div = '';
    var $postVar = '';
    var $additional_param = '';
    // Added by Sean
    var $anchor_class = '';
    var $show_count = true;

    /**
     * Constructor
     *
     * @access	public
     * @param	array	initialization parameters
     */
    function CI_Pagination($params = array()) {
        if (count($params) > 0) {
            $this->initialize($params);
        }

        log_message('debug', "Pagination Class Initialized");
    }

    // --------------------------------------------------------------------

    /**
     * Initialize Preferences
     *
     * @access	public
     * @param	array	initialization parameters
     * @return	void
     */
    function initialize($params = array()) {
        if (count($params) > 0) {
            foreach ($params as $key => $val) {
                if (isset($this->$key)) {
                    $this->$key = $val;
                }
            }
        }

        // Apply class tag using anchor_class variable, if set.
        if ($this->anchor_class != '') {
            $this->anchor_class = 'class="' . $this->anchor_class . '" ';
        }
    }

    // --------------------------------------------------------------------

    /**
     * Generate the pagination links
     *
     * @access	public
     * @return	string
     */
    function create_links() {
        // If our item count or per-page total is zero there is no need to continue.
        if ($this->total_rows == 0 OR $this->per_page == 0) {
            return '';
        }

        // Calculate the total number of pages
        $num_pages = ceil($this->total_rows / $this->per_page);

        // Is there only one page? Hm... nothing more to do here then.
        if ($num_pages == 1) {
            $info = '<div class=""><p class="no-of-records">Displaying ' . $this->total_rows . ' records</p></div>';
            return $info;
        }


        // Determine the current page number.
        $CI = & get_instance();
        if ($CI->uri->segment($this->uri_segment) != 0) {
            $this->cur_page = $CI->uri->segment($this->uri_segment);

            // Prep the current page - no funny business!
            $this->cur_page = (int) $this->cur_page;
        }

        $this->num_links = (int) $this->num_links;

       if ($this->num_links < 1) {
           show_error('Your number of links must be a positive number.');
       }

        if (!is_numeric($this->cur_page)) {
            $this->cur_page = 0;
        }

        // Is the page number beyond the result range?
        // If so we show the last page
        if ($this->cur_page > $this->total_rows)
        {
            $this->cur_page = ($num_pages - 1) * $this->per_page;
        }

        $uri_page_number = $this->cur_page;
        $this->cur_page = floor(($this->cur_page/$this->per_page) + 1);

        // Calculate the start and end numbers. These determine
        // which number to start and end the digit links with
        $start = (($this->cur_page - $this->num_links) > 0) ? $this->cur_page - ($this->num_links - 1) : 1;
        $end   = (($this->cur_page + $this->num_links) < $num_pages) ? $this->cur_page + $this->num_links : $num_pages;

        // Add a trailing slash to the base URL if needed
        $this->base_url = rtrim($this->base_url, '/') . '/';

        // And here we go...
        $output = '';

        // SHOWING LINKS
        if ($this->show_count)
        {
            $curr_offset = $CI->uri->segment($this->uri_segment);
            $info       = '<div class="col-sm-5"><p class="no-of-records">Displaying ' . ( $curr_offset + 1 ) . ' to ' ;
            $mearg      = $curr_offset + $this->per_page;
            $diduct_one = $this->total_rows - 1;
            
            if( ( $mearg ) <= ( $diduct_one ) )
            {
                $info .= $curr_offset + $this->per_page;
            }
            else
            {
                /*if($this->total_rows == '11')
                {
                    $info .= '10';
                }
                else 
                {*/
                    $info .= $this->total_rows;
                
            }
            
            $info .= ' records out of ' . $this->total_rows . ' </p></div> <div class="col-sm-7"><ul class="pagination pull-right m-0">';

            $output .= $info;
        }

        
        // Render the "First" link
        if  ($this->cur_page > $this->num_links)
        {
            $link=lang('first');
            $output .= $this->first_tag_open.'<li class="paginate_button previous disabled">'
                . $this->getAJAXlink( '' , '&lsaquo; '.$link).'</li>'
                . $this->first_tag_close;
        }

        // Render the "previous" link
        if ($this->cur_page != 1) {
            $i = $uri_page_number - $this->per_page;
            if ($i == 0)
                $i = '';
            $output .= $this->prev_tag_open . '<li class="paginate_button previous disabled">'
                    . $this->getAJAXlink($i, $this->prev_link) . '</li>'
                    . $this->prev_tag_close;
        }


        //Write the digit links
       for ($loop = $start - 1; $loop <= $end; $loop++) {
           $i = ($loop * $this->per_page) - $this->per_page;

           if ($i >= 0) {
               if ($this->cur_page == $loop) {
                   $output .= $this->cur_tag_open . $loop . $this->cur_tag_close; // Current page
               } else {
                   $n = ($i == 0) ? '' : $i;
                   $output .= $this->num_tag_open . '<li class="paginate_button previous disabled">'
                           . $this->getAJAXlink($n, $loop) . '</li>'
                           . $this->num_tag_close;
               }
           }
       }

        // Render the "next" link
        if ($this->cur_page < $num_pages) {
            $output .= $this->next_tag_open . '<li class="paginate_button previous disabled">'
                    . $this->getAJAXlink($this->cur_page * $this->per_page, $this->next_link)
                    . '</li>' . $this->next_tag_close;
        }


        // Render the "Last" link
        if (($this->cur_page + $this->num_links) < $num_pages) {
            $i = (($num_pages * $this->per_page) - $this->per_page);
            $linkLast = ($this->last_link != '') ? $this->last_link : lang('last');

            $output .= $this->last_tag_open .'<li class="paginate_button previous disabled">'. $this->getAJAXlink( $i, lang('last').' '.$this->last_link ).'</li>' . $this->last_tag_close;
        }

        // Kill double slashes.  Note: Sometimes we can end up with a double slash
        // in the penultimate link so we'll kill all double slashes.
        $output = preg_replace("#([^:])//+#", "\\1/", $output);

        // Add the wrapper HTML if exists
        $output = $this->full_tag_open . $output . $this->full_tag_close . '</ul></div>';

        return $output;
    }

    function getAJAXlink($count, $text) {

        if ($this->div == '')
            return '<li ><a class="ajax_paging" href="' . $this->anchor_class . ' ' . $this->base_url . $count . '">' . $text . '</a></li>';

        $pageCount = $count ? $count : 0;
        $this->additional_param = "{'page' : $pageCount}";

        /* return "<a href=\"javascript:void(0);\"
          " . $this->anchor_class . "
          onclick=\"$.post('". $this->base_url . $count ."', ". $this->additional_param .", function(data){
          $('#". $this->div . "').html(data)" . $this->js_rebind ."; }); return false;\">"
          . $text .'</a>'; */
        return '<li >' . "<a href=" . $this->base_url . $count . "
		         " . $this->anchor_class . " class='ajax_paging'
					>"
                . $text . '</a></li>';
    }

}

// END Pagination Class
?>
