<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$plugin_info = array(
    'pi_name'           => 'Fix Playa Field Data',
    'pi_version'        => '1.0',
    'pi_author'         => 'Nathan Pitman',
    'pi_author_url'     => 'http://ninefour.co.uk/labs',
    'pi_description'    => 'Fixes out of whack Playa custom fields',
    'pi_usage'          => Nf_fix_playa_field_data::usage()
);

/**
 * Nf_fix_playa_field_data Class
 *
 * @package         ExpressionEngine
 * @category        Plugin
 * @author          Nathan Pitman @ Nine Four Ltd
 * @copyright       Copyright (c) 20014 Nine Four Ltd.
 * @link            http://ninefour.co.uk/labs
 */

class Nf_fix_playa_field_data {

    var $return_data;

    /**
     * Constructor
     *
     */
    function Nf_fix_playa_field_data()
    {

        if (!$field_id = ee()->TMPL->fetch_param('field_id')) {
            return;
        } else {

            $success = 0;

            $result = array();
            $result = ee()->db->query("SELECT entry_id, channel_id FROM exp_channel_data WHERE field_id_".$field_id."<>''");

            foreach($result->result_array() AS $row) {

                $rels = array();
                $rels = ee()->db->query("SELECT child_entry_id FROM exp_playa_relationships WHERE parent_entry_id=".$row['entry_id']."");
                $new_field_data = "";

                foreach($rels->result_array() AS $rel) {
                    $entry = array();
                    $entry = ee()->db->query("SELECT entry_id, title, url_title FROM exp_channel_titles WHERE entry_id=".$rel['child_entry_id']."");
                    $entry = $entry->row_array();
                    // [entry_id] [url_title] title
                    $new_field_data .= "[".$entry['entry_id']."] [".$entry['url_title']."] ".$entry['title']."\n";
                }

                $data = array();
                $data['field_id_'.$field_id] = rtrim($new_field_data);

                // update existing exp_channel_data row with new data
                ee()->db->where('entry_id', $row['entry_id']);
                ee()->db->update('exp_channel_data', $data);

                $success++;

            }

            // We're done
            $response = "Updated ".$success." exp_channel_data table rows with correct Playa field data.";

        }

        $this->return_data = $response;
    }

    // --------------------------------------------------------------------

    /**
     * Usage
     *
     * Plugin Usage
     *
     * @access  public
     * @return  string
     */
    function usage()
    {
        ob_start();
        ?>
        Pass a field_id which requires fixing and this plug-in will loop over all entries and repair the playa field data in that field using the exp_playa_relationships table as a reference.

        {exp:nf_fix_playa_field_data field_id="59"}

        You should take a backup of your database before using the plug-in in a live environment.

        <?php
        $buffer = ob_get_contents();

        ob_end_clean();

        return $buffer;
    }

    // --------------------------------------------------------------------

}
// END CLASS

/* End of file pi.nf_fix_playa_field_data.php */
/* Location: ./system/expressionengine/third_party/nf_fix_playa_field_data/pi.nf_fix_playa_field_data.php */