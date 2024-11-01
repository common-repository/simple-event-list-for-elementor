<?php

global $wpdb;
$table_name = TABLE_NAME_MIGA_SIMPLE_EVENTS;
$results = $wpdb->get_results("SELECT * FROM $table_name WHERE visible=1 AND (dateFrom >= CURRENT_DATE OR (dateFrom <= CURRENT_DATE AND dateTo >= CURRENT_DATE)) ORDER BY dateFrom, timeFrom");

require("constants.php");

if (empty($noItemText)) {
    $noItemText = "";
}

$hasMore = "";
if (isset($maxAmount) && (int) $maxAmount > -1) {
    $results = array_slice($results, 0, (int) $maxAmount);
    $hasMore = " hasMore";
}

echo '<div class="miga_simple_events_container'.$hasMore.'" style="height:'.esc_attr($height).'">';
echo '<ul class="miga_simple_events_ul miga_simple_events_theme1">';
if (!empty($results)) {

    foreach ($results as $row) {
        $dateD = date("d", strtotime(esc_html($row->dateFrom)));
        $dateM = date("m", strtotime(esc_html($row->dateFrom)));
        $dateY = date("Y", strtotime(esc_html($row->dateFrom)));
        $dateM = $monthNames[(int) $dateM - 1];
        $dayName = date("N", strtotime(esc_html($row->dateFrom)));
        $dayName = $dayNames[$dayName];

        $timeFrom = esc_html($row->timeFrom);
        $timeFromOrg = esc_html($row->timeFrom);

        $timeString = "";
        if (!empty($timeFrom)) {
            $timeFrom = explode(":", $timeFrom);
            $timeString .= '<div class="miga_simple_events_time">';
            $timeString .=  __("from", "simple-event-list-for-elementor");

            if (!$usTime) {
                $timeString .=  ' '.$timeFrom[0].":".$timeFrom[1]. " ";
                $timeString .= __("h", "simple-event-list-for-elementor");
            } else {
                $timeString .= ' '.date('h:i A', strtotime($timeFromOrg));
            }
            $timeString .= "</div>";
        }

        echo '<li class="miga_simple_events_flex">';
        echo '<div class="miga_simple_events_date">';
        if (!empty($row->link)) {
            echo '<a href="'.get_permalink($row->link).'" class="miga_simple_events_date_link"></a>';
        }
        if ($showDayName) {
            echo '<div class="miga_simple_events_dayName">'.esc_html($dayName).'</div>';
        }
        echo '<div class="miga_simple_events_day">'.esc_html($dateD).'</div>';
        echo '<div class="miga_simple_events_month">'.esc_html($dateM).'</div>';
        if ($showYear) {
            echo '<div class="miga_simple_events_year">'.esc_html($dateY).'</div>';
        }

        echo '</div>';

        if (!isset($row->dateTo)) {
            echo '<div class="miga_simple_events_text">';
            echo '<div class="miga_simple_events_text_content">';
            if (!empty($row->link)) {
                echo '<a href="'.get_permalink($row->link).'">';
            }
            echo esc_html($row->text);
            if (!empty($row->link)) {
                echo '</a>';
            }
            echo '</div>';
            if (!empty($timeString) && $showTimeFrom) {
                echo wp_kses_post($timeString);
            }
            echo '</div>';
        }

        if (isset($row->dateTo)) {
            $dateD = date("d", strtotime(esc_html($row->dateTo)));
            $dateM = date("m", strtotime(esc_html($row->dateTo)));
            $dateY = date("Y", strtotime(esc_html($row->dateTo)));
            $dateM = $monthNames[(int) $dateM - 1];
            $dayName = date("N", strtotime(esc_html($row->dateTo)));
            $dayName = $dayNames[$dayName];

            $time = esc_html($row->timeTo);
            $timeOrg = esc_html($row->timeTo);
            $timeString = "";
            if (!empty($time)) {
                $time = explode(":", $time);
                $timeString = '<div class="miga_simple_events_time">';

                $timeString .=  __("until", "simple-event-list-for-elementor");
                if (!$usTime) {
                    $timeString .=  ' '.$time[0].":".$time[1]. " ";
                    $timeString .=  __("h", "simple-event-list-for-elementor");
                } else {
                    $timeString .= ' '.date('h:i A', strtotime($timeOrg));
                }

                $timeString .=  "</div>";
            }

            echo '<div class="miga_simple_events_date miga_simple_events_dateTo">';
            if ($showDayName) {
                echo '<div class="miga_simple_events_dayName">'.esc_html($dayName).'</div>';
            }
            echo '<div class="miga_simple_events_day">'.esc_html($dateD).'</div>';
            echo '<div class="miga_simple_events_month">'.esc_html($dateM).'</div>';
            if ($showYear) {
                echo '<div class="miga_simple_events_year">'.esc_html($dateY).'</div>';
            }
            echo '</div>';
            echo '<div class="miga_simple_events_text">';
            if (!empty($timeFrom) && $showTimeFrom) {

                if (!$usTime) {
                    echo wp_kses_post($timeFrom[0].":".$timeFrom[1]);
                } else {
                    echo wp_kses_post(date('h:i A', strtotime($timeFromOrg)));
                }

            }
            echo '<div class="miga_simple_events_text_content">';
            if (!empty($row->link)) {
                echo '<a href="'.get_permalink($row->link).'">';
            }
            echo esc_html($row->text);
            if (!empty($row->link)) {
                echo '</a>';
            }
            echo '</div>';
            if (!empty($timeString) && $showTimeTo) {
                echo wp_kses_post($timeString);
            }
            echo '</div>';
        }

        echo '</li>';
    }
} else {
    echo "<i>".$noItemText."</i>";
}
echo '</ul>';
echo '</div>';
