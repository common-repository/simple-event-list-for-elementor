<?php

global $wpdb;
$table_name = TABLE_NAME_MIGA_SIMPLE_EVENTS;
$results = $wpdb->get_results("SELECT * FROM $table_name WHERE visible=1 AND (dateFrom >= CURRENT_DATE OR (dateFrom <= CURRENT_DATE AND dateTo >= CURRENT_DATE)) ORDER BY dateFrom, timeFrom");

require("constants.php");

$hasMore = "";
if (isset($maxAmount) && (int) $maxAmount > -1) {
    $results = array_slice($results, 0, (int) $maxAmount);
    $hasMore = " hasMore";
}

echo '<div class="miga_simple_events_container'.$hasMore.'" style="height:'.esc_attr($height).'">';
echo '<ul class="miga_simple_events_ul miga_simple_events_theme2">';
if (!empty($results)) {

    foreach ($results as $row) {
        if (!$usDate) {
            $date = date("d.m.Y", strtotime(esc_html($row->dateFrom)));
        } else {
            $date = date("m/d/Y", strtotime(esc_html($row->dateFrom)));
        }
        $timeFrom = $row->timeFrom;
        $timeFromOrg = $row->timeFrom;

        echo '<li class="miga_simple_events_flex">';
        echo '<div class="miga_simple_events_date">';
        echo esc_html($date);

        if (!empty($timeFrom)) {
            $timeFrom = explode(":", $timeFrom);

            if (!$usTime) {
                echo ' - '.$timeFrom[0].":".$timeFrom[1]. " ". __("h", "simple-event-list-for-elementor");
            } else {
                echo ' - '.date('h:i A', strtotime($timeFromOrg));
            }

        }
        echo '</div>';
        echo '<div class="miga_simple_events_text_content">';
        if (!empty($row->link)) {
            echo '<a href="'.get_permalink($row->link).'">';
        }
        echo esc_html($row->text);
        if (!empty($row->link)) {
            echo '</a>';
        }

        echo '</div>';

        // if (!isset($row->dateTo)) {
        //     echo '<div class="miga_simple_events_text">';
        //     echo '<div class="miga_simple_events_text_content">'.esc_html($row->text).'</div>';
        //     if (!empty($timeFrom) && $showTimeFrom) {
        //         echo wp_kses_post($timeFrom);
        //     }
        //     echo '</div>';
        // }
        /*
                if (isset($row->dateTo)) {

                  $dateD = date("d", strtotime(esc_html($row->dateTo)));
                  $dateM = date("m", strtotime(esc_html($row->dateTo)));
                  $dateY = date("Y", strtotime(esc_html($row->dateTo)));
                  $dateM = $monthNames[(int) $dateM - 1];
                  $dayName = date("N", strtotime(esc_html($row->dateTo)));
                  $dayName = $dayNames[$dayName];

                  $time = esc_html($row->timeTo);
                  if (!empty($time)) {
                      $time = explode(":", $time);
                      $time = '<div class="miga_simple_events_time">bis '.$time[0].":".$time[1]. " Uhr</div>";
                  }

                  echo '<div class="miga_simple_events_date miga_simple_events_dateTo">';
                  if ($showDayName) echo '<div class="miga_simple_events_dayName">'.esc_html($dayName).'</div>';
                  echo '<div class="miga_simple_events_day">'.esc_html($dateD).'</div>';
                  echo '<div class="miga_simple_events_month">'.esc_html($dateM).'</div>';
                  if ($showYear) echo '<div class="miga_simple_events_year">'.esc_html($dateY).'</div>';
                  echo '</div>';
                  echo '<div class="miga_simple_events_text">';
                  if (!empty($timeFrom) && $showTimeFrom) {
                      echo wp_kses_post($timeFrom);
                  }
                  echo '<div class="miga_simple_events_text_content">'.esc_html($row->text).'</div>';
                  if (!empty($time) && $showTimeTo) {
                      echo wp_kses_post($time);
                  }
                  echo '</div>';
                }
        */
        echo '</li>';
    }
}
echo '</ul>';
echo '</div>';
