<?php

global $wpdb;
$table_name = TABLE_NAME_MIGA_SIMPLE_EVENTS;

if (isset($_POST["submit"])) {
    $dateTo = empty($_POST["dateTo"]) ? null : sanitize_text_field($_POST["dateTo"]);
    $timeFrom = empty($_POST["timeFrom"]) ? null : sanitize_text_field($_POST["timeFrom"]);
    $timeTo = empty($_POST["timeTo"]) ? null : sanitize_text_field($_POST["timeTo"]);

    $wpdb->insert($table_name, [
        "text" => sanitize_text_field($_POST["text"]),
        "dateFrom" => sanitize_text_field($_POST["dateFrom"]),
        "timeFrom" => $timeFrom,
        "dateTo" => $dateTo,
        "timeTo" => $timeTo,
    ]);
}

$results = $wpdb->get_results("SELECT * FROM $table_name  ORDER BY dateFrom, timeFrom");
?>
<div class="flex">
<form class="miga_calendar_half" method="post" action="?page=miga_simple_events-page">
<table class="miga_calendar_status">
<thead>
  <tr>
    <th class="th_small"><?php echo esc_html__("Visible", "miga_simple_events"); ?></th>
    <th>Date (from)</th>
    <th>Time (from)</th>
    <th>Date (to)</th>
    <th>Time (to)</th>
    <th>Text</th>
  </tr>
</thead>
<tbody>
<?php if (!empty($results)) {
    foreach ($results as $row) {
        $timeFrom = esc_html($row->timeFrom);
        if (!empty($timeFrom)) {
            $timeFrom = explode(":", $timeFrom);
            $timeFrom = 'value="'.$timeFrom[0].":".$timeFrom[1].'"';
        } else {
            $timeFrom = "";
        }
        $timeTo = esc_html($row->timeTo);
        if (!empty($timeTo)) {
            $timeTo = explode(":", $timeTo);
            $timeTo = 'value="'.$timeTo[0].":".$timeTo[1].'"';
        } else {
            $timeTo = "";
        }
        $timeTo = esc_html($row->timeTo);
        if (!empty($timeTo)) {
            $timeTo = explode(":", $timeTo);
            $timeTo = 'value="'.$timeTo[0].":".$timeTo[1].'"';
        } else {
            $timeTo = "";
        }

        $dateTo = null;
        if (!empty($row->dateTo)) {
            $dateTo = 'value="' . esc_html($row->dateTo) . '"';
        }

        echo "<tr>";
        echo '<td class="th_small"><input type="checkbox" class="tbl_visible" ' . ($row->visible ? "checked" : "") .' value="1"/></td>';
        echo '<td><input type="date" class="tbl_dateFrom" value="' . esc_html($row->dateFrom) . '"/></td>';
        echo '<td><input type="time" class="tbl_timeFrom" ' . wp_kses_post($timeFrom) . '/></td>';
        echo '<td><input type="date" class="tbl_dateTo" ' . wp_kses_post($dateTo).'/></td>';
        echo '<td><input type="time" class="tbl_timeTo" ' . wp_kses_post($timeTo) . '/></td>';
        echo '<td><input type="text" class="tbl_text" value="' . esc_html($row->text) . '"/></td>';
        echo "<td>";
        echo '<button data-value="'. (int) esc_attr($row->id) .'" onclick="return miga_simple_events_updateItem(this);">update</button>';
        echo '<button data-value="' .(int) esc_attr($row->id) .'" onclick="return miga_simple_events_deleteItem(this);">delete</button>';
        echo "</td>";
        echo "</tr>";
    }
} ?>
<tr><td colspan="5"><hr/></td></tr>

<tr>
  <td></td>
  <td><b>From:</b></td>
  <td></td>
  <td><b>To:</b></td>
  <td></td>
  <td></td>
  <td></td>
</tr>
<tr>
  <td>Add:</td>
  <td><input type="date" name="dateFrom" id="dateFrom" value=""/></td>
  <td><input type="time" name="timeFrom" id="timeFrom" /></td>
  <td><input type="date" name="dateTo" id="dateTo" value=""/></td>
  <td><input type="time" name="timeTo" id="timeTo" /></td>
  <td><input type="text" value="" name="text" id="class" placeholder="Text"/></td>
  <td class="submit_button"><?php echo submit_button(esc_html__("add", "miga_simple_events")); ?></td>
</tr>
</tbody>
</table>
</form>
<hr/>
<h2>How to use it:</h2>
<h3>Elementor</h3>
<p>Use the <code>Simple event</code> widget.</p>
<h3>Shortcode</h3>
<p>Use <code>[miga_simple_events]</code> to include it as a shortcode.</p>
<h4>Parameter</h4>
<ul>
  <li><b>height:</b> max height of the container</li>
  <li><b>showdayname:</b> 0/1 to display the day name</li>
  <li><b>showyear:</b> 0/1 to display the year</li>
</ul>
<h3>Example</h3>
<p>
<code>[miga_simple_events height=200]</code>
</p>
</div>
