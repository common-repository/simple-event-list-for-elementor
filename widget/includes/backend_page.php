<?php

global $wpdb;
$table_name = TABLE_NAME_MIGA_SIMPLE_EVENTS;

$posts = get_posts();
$postOptions = array();
foreach ($posts as $post) {
    $postOptions[$post->ID] = $post->post_title;
}

if (isset($_POST["submit"])) {
    if ($_POST["submit"] == __("add", 'simple-event-list-for-elementor')) {
        $dateTo = empty($_POST["dateTo"]) ? null : sanitize_text_field($_POST["dateTo"]);
        $timeFrom = empty($_POST["timeFrom"]) ? null : sanitize_text_field($_POST["timeFrom"]);
        $timeTo = empty($_POST["timeTo"]) ? null : sanitize_text_field($_POST["timeTo"]);
        $link = empty($_POST["link"]) ? null : sanitize_text_field($_POST["link"]);

        if (isset($_POST["dateFrom"]) && !empty($_POST["dateFrom"])) {
            $wpdb->insert($table_name, [
                "text" => sanitize_text_field($_POST["text"]),
                "dateFrom" => sanitize_text_field($_POST["dateFrom"]),
                "timeFrom" => $timeFrom,
                "dateTo" => $dateTo,
                "timeTo" => $timeTo,
                "link" => $link,
            ]);
        }
    } else {
        global $wpdb;

        $dateFrom = sanitize_text_field($_POST["dateFrom"]);
        $timeFrom = sanitize_text_field($_POST["timeFrom"]);
        $dateTo = sanitize_text_field($_POST["dateTo"]);
        $timeTo = sanitize_text_field($_POST["timeTo"]);
        $link = sanitize_text_field($_POST["link"]);

        if (empty($dateFrom)) {
            $dateFrom = null;
        }
        if (empty($timeFrom)) {
            $timeFrom = null;
        }
        if (empty($dateTo)) {
            $dateTo = null;
        }
        if (empty($timeTo)) {
            $timeTo = null;
        }
        if (empty($link)) {
            $link = null;
        }

        $wpdb->update(
            TABLE_NAME_MIGA_SIMPLE_EVENTS,
            [
                "text" => sanitize_text_field($_POST["text"]),
                "dateFrom" => $dateFrom,
                "timeFrom" => $timeFrom,
                "dateTo" => $dateTo,
                "timeTo" => $timeTo,
                "link" => $link,
            ],
            ["id" => (int) sanitize_text_field($_POST["eventId"])]
        );
    }
}

$dateFrom = "";
$timeFrom = "";
$dateTo = "";
$timeTo = "";
$text = "";
$link = "";
$isEdit = false;
$id = "";

if (isset($_GET["action"])) {
    if ($_GET["action"] === "edit") {
        $isEdit = true;
        $element = (int) sanitize_text_field($_GET["element"]);
        $results = $wpdb->get_results("SELECT * FROM $table_name WHERE id=".$element);
        $dateFrom = $results[0]->dateFrom;
        $timeFrom = $results[0]->timeFrom;
        $dateTo = $results[0]->dateTo;
        $timeTo = $results[0]->timeTo;
        $text = $results[0]->text;
        $link = $results[0]->link;
        $id = $element;
    } elseif ($_GET["action"] === "delete") {
        $element = (int) sanitize_text_field($_GET["element"]);
        $wpdb->delete($table_name, [
            "id" => $element
        ]);
    } elseif ($_GET["action"] === "toggle_visibility") {
        $element = (int) sanitize_text_field($_GET["element"]);
        $results = $wpdb->get_results("UPDATE $table_name SET visible = NOT visible WHERE id=".$element);
    }
}
?>
<div class="flex">
  <h2><?=__("New event", "simple-event-list-for-elementor");?></h2>
<form class="miga_calendar_half" method="post" action="?page=miga_simple_events-page">
<table class="miga_calendar_status">
<thead>
  <tr>
    <th><?=__('From (date)', 'simple-event-list-for-elementor');?></th>
    <th><?=__('From (time)', 'simple-event-list-for-elementor');?></th>
    <th><?=__('To (date)', 'simple-event-list-for-elementor');?></th>
    <th><?=__('To (time)', 'simple-event-list-for-elementor');?></th>
    <th><?= __('Text', 'simple-event-list-for-elementor');?></th>
    <th><?=__('Link', 'simple-event-list-for-elementor');?></th>
    <th></th>
  </tr>
</thead>
<tbody>
<tr>
  <input type="hidden" id="eventId" name="eventId" value="<?=$id;?>"/>
  <td><input type="date" name="dateFrom" id="dateFrom" value="<?=$dateFrom;?>"/></td>
  <td><input type="time" name="timeFrom" id="timeFrom" value="<?=$timeFrom;?>"/></td>
  <td><input type="date" name="dateTo" id="dateTo" value="<?=$dateTo;?>"/></td>
  <td><input type="time" name="timeTo" id="timeTo" value="<?=$timeTo;?>"/></td>
  <td><input type="text" value="<?=$text;?>" name="text" id="text" placeholder="Text"/></td>
  <td><select class="link" id="link" name="link">
    <option>-</option>
    <?php foreach ($postOptions as $key => $value):
        $selected = "";
        if ($link == $key) {
            $selected=" selected ";
        }
        echo '<option value="'.esc_html($key).'" '.esc_html($selected).'>'.esc_html($value).'</option>';
    endforeach;
?>
 </select></td>
  <td class="submit_button"><?php
    if ($isEdit) {
        echo submit_button(__("update", "simple-event-list-for-elementor"));
    } else {
        echo submit_button(__("add", "simple-event-list-for-elementor"));
    }
?></td>
</tr>
</tbody>
</table>
<hr/>

<h2><?=__("Current events", "simple-event-list-for-elementor");?></h2>
<?php

require "table_class.php";
$table = new My_List_Table();
$table->prepare_items();
$table->display();

?>

</form>
</div>
