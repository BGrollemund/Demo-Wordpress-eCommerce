<?php


class class_wp_widget_ratings extends WP_Widget {

    public function __construct() {

        $widget_options = [
            'classname' => 'wp_widget_ratings',
            'description' => __('Suivi du classement général'),
            'customize_selective_refresh' => true,
        ];

        parent::__construct( 'widget_ratings', __('Classement général', 'global rating'), $widget_options );
    }

    public function form( $instance ) {

        $ins = new inscription();

        $instance = wp_parse_args( (array) $instance, array('season'=>'', 'domain'=>'', 'number'=>''));

        echo '<p>Affichage du classement général :</p>';


        echo '<label for="'.$this->get_field_id('season').'"></label>';
        echo '<select name="'.$this->get_field_name('season').'" id="'.$this->get_field_id('season').'">';
        echo '<option value="">Choisissez la saison</option>';
        foreach( $ins->findAllSeason() as $line ) {
            echo '<option value="'. $line['season'] .'">'. $line['season'] .'</option>';
        }
        echo '</select>';

        echo '<label for="'.$this->get_field_id('domain').'"></label>';
        echo '<select name="'.$this->get_field_name('domain').'" id="'.$this->get_field_id('domain').'">';
        echo '<option value="">Choisissez le domaine</option>';
        foreach( $ins->findAllDomain() as $line ) {
            echo '<option value="'. $line['domain'] .'">'. $line['domain'] .'</option>';
        }
        echo '</select>';
        echo '<br>';

        echo '<label for="'.$this->get_field_id('number').'">Nombre de participants affichés :</label>';
        echo '<input class="widefat" id="'.$this->get_field_id('number').'" '.
            'type="text" name="'.$this->get_field_name('number').'" '.
            'value="'.esc_attr($instance['number']).'">';
    }

    public function widget( $args, $instance ) {
        $title = 'Classement général';

        $ins = new inscription();

        echo $args['before_widget'];
        if ( $title ) {
            echo $args['before_title'] . $title . '<br>' . $instance['season'] .' : '. $instance['domain'] . $args['after_title'];
        }

        $rating = $ins->calculateRatings( $instance['season'], $instance['domain'], $instance['number'] );

        if( ! empty($rating) ) {

            $i = 1;

            foreach( $rating as $key => $value ) {
                echo '<p>';
                echo $i.'. '.$key.' ('.$value.' points)';
                echo '</p>';
                $i++;
            }
        }
        echo $args['after_widget'];
    }
}