<?php

class MIGA_SimpleEventWidget extends \Elementor\Widget_Base
{
    public function get_name()
    {
        return "miga_simple_events";
    }

    public function get_title()
    {
        return __("Simple events", "simple-event-list-for-elementor");
    }

    public function get_icon()
    {
        return "eicon-calendar";
    }

    public function get_categories()
    {
        return ["general"];
    }

    public function addStyling($obj, $group)
    {
        $obj->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'content_typography'.$group,
                'selector' => '{{WRAPPER}} .miga_simple_events_container .miga_simple_events_'.$group,
            ]
        );
        $obj->add_control(
            'text_color_'.$group,
            [
                'label' => esc_html__('Text Color', 'miga_simple_events'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .miga_simple_events_container .miga_simple_events_'.$group => 'color: {{VALUE}}',
                ],
            ]
        );
    }

    protected function _register_controls()
    {
        $this->start_controls_section("content_section", [
            "label" => __("Layout", "simple-event-list-for-elementor"),
            "tab" => \Elementor\Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_control(
            'theme',
            [
                'label' => esc_html__('Theme', 'miga_simple_events'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'theme1',
                'options' => [
                    'theme1' => esc_html__('Theme 1', 'miga_simple_events'),
                    'theme2' => esc_html__('Theme 2', 'miga_simple_events'),
                ]
            ]
        );


        $this->add_control(
            'showDayName',
            [
                'label' => esc_html__('Show day names', 'miga_simple_events'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', 'miga_simple_events'),
                'label_off' => esc_html__('Hide', 'miga_simple_events'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'showYear',
            [
                'label' => esc_html__('Show year', 'miga_simple_events'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', 'miga_simple_events'),
                'label_off' => esc_html__('Hide', 'miga_simple_events'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'showTimeFrom',
            [
                'label' => esc_html__('Show time (from)', 'miga_simple_events'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', 'miga_simple_events'),
                'label_off' => esc_html__('Hide', 'miga_simple_events'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );
        $this->add_control(
            'showTimeTo',
            [
                'label' => esc_html__('Show time (to)', 'miga_simple_events'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', 'miga_simple_events'),
                'label_off' => esc_html__('Hide', 'miga_simple_events'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'showNoitems',
            [
                'label' => esc_html__('Show "no events" text', 'miga_simple_events'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', 'miga_simple_events'),
                'label_off' => esc_html__('Hide', 'miga_simple_events'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );
        $this->add_control(
            'noItemText',
            [
                'label' => esc_html__('"No events" text', 'miga_simple_events'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__('Currently no events', 'miga_simple_events'),
                'placeholder' => esc_html__('Type your text here', 'miga_simple_events'),
                'condition' => [
                    'showNoitems' => 'yes',
                ],
            ]
        );
        $this->add_control(
            'usTime',
            [
                'label' => esc_html__('US Time', 'miga_simple_events'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'miga_simple_events'),
                'label_off' => esc_html__('No', 'miga_simple_events'),
                'return_value' => 'yes',
                'default' => 'no',
            ]
        );
        $this->add_control(
            'usDate',
            [
                'label' => esc_html__('US Date', 'miga_simple_events'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'miga_simple_events'),
                'label_off' => esc_html__('No', 'miga_simple_events'),
                'return_value' => 'yes',
                'default' => 'no',
            ]
        );


        $this->add_responsive_control(
            'height',
            [
                'type' => \Elementor\Controls_Manager::SLIDER,
                'label' => esc_html__('Fixed height', 'miga_simple_events'),
                'size_units' => [ 'px', 'em', '%', 'custom' ],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 1000,
                    'step' => 2,
                ],
                '%' => [
                    'min' => 0,
                    'max' => 100,
                ],
            ],
            'default' => [
                'unit' => 'px',
                'size' => null,
            ],
                'selectors' => [
                    '{{WRAPPER}} .miga_simple_events_container' => 'max-height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'margin_li',
            [
                'type' => \Elementor\Controls_Manager::SLIDER,
                'label' => esc_html__('Item spacing', 'miga_simple_events'),
                'size_units' => [ 'px', 'em', '%', 'custom' ],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 400,
                    'step' => 2,
                ],
                '%' => [
                    'min' => 0,
                    'max' => 100,
                ],
            ],
            'default' => [
                'unit' => 'px',
                'size' => 10,
            ],
                'selectors' => [
                    '{{WRAPPER}} .miga_simple_events_container li' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'maxAmount',
            [
                'label' => esc_html__('max. amount of events to show', 'miga_simple_events'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => -1,
                'max' => 100,
                'step' => 1,
                'default' => -1,
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'content_style_day',
            [
                'label' => esc_html__('Day', 'miga_simple_events'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        $this->addStyling($this, "day");
        $this->end_controls_section();

        $this->start_controls_section(
            'content_style_dayName',
            [
                'label' => esc_html__('Day name', 'miga_simple_events'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        $this->addStyling($this, "dayName");
        $this->end_controls_section();

        $this->start_controls_section(
            'content_style_month',
            [
                'label' => esc_html__('Month', 'miga_simple_events'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        $this->addStyling($this, "month");
        $this->end_controls_section();

        $this->start_controls_section(
            'content_style_year',
            [
                'label' => esc_html__('Year', 'miga_simple_events'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        $this->addStyling($this, "year");
        $this->end_controls_section();


        $this->start_controls_section(
            'content_style_bg',
            [
                'label' => esc_html__('Background box', 'miga_simple_events'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'background',
                'types' => [ 'classic', 'gradient', 'video' ],
                'selector' => '{{WRAPPER}} .miga_simple_events_container .miga_simple_events_date',
            ]
        );

        $this->add_control(
            'border_bg',
            [
                'label' => esc_html__('Border radius', 'miga_simple_events'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .miga_simple_events_container .miga_simple_events_date' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'padding_bg',
            [
                'label' => esc_html__('Padding', 'miga_simple_events'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .miga_simple_events_container .miga_simple_events_date' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_section();

        $this->start_controls_section(
            'content_style_text',
            [
                'label' => esc_html__('Text', 'miga_simple_events'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        $this->addStyling($this, "text_content");
        $this->end_controls_section();

        $this->start_controls_section(
            'content_style_time',
            [
                'label' => esc_html__('Time', 'miga_simple_events'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        $this->addStyling($this, "time");
        $this->end_controls_section();
    }

    public function get_style_depends()
    {
        wp_register_style(
            "miga_simple_events_style",
            plugins_url("../styles/main.css", __FILE__)
        );

        return ["miga_simple_events_style"];
    }

    protected function render()
    {
        $isEditor = \Elementor\Plugin::$instance->editor->is_edit_mode();
        $settings = $this->get_settings_for_display();
        $height = "auto";
        $showDayName = ($settings["showDayName"] === "yes");
        $showYear = ($settings["showYear"] === "yes");
        $showTimeFrom = ($settings["showTimeFrom"] === "yes");
        $showTimeTo = ($settings["showTimeTo"] === "yes");
        $theme = $settings["theme"];
        $noItemText = $settings["noItemText"];
        $maxAmount = (int)$settings["maxAmount"];
        $usTime = ($settings["usTime"] === "yes");
        $usDate = ($settings["usDate"] === "yes");

        if ($theme == "theme1") {
            require("includes/theme1.php");
        } elseif ($theme == "theme2") {
            require("includes/theme2.php");
        }
    }

    protected function _content_template()
    {
    }
}
